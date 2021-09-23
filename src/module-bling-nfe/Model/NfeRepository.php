<?php
/**
* 
* Bling NFe para Magento 2
* 
* @category     elOOm
* @package      Modulo Bling NFe
* @copyright    Copyright (c) 2021 elOOm (https://eloom.tech)
* @version      1.0.0
* @license      https://opensource.org/licenses/OSL-3.0
* @license      https://opensource.org/licenses/AFL-3.0
*
*/
declare(strict_types=1);

namespace Eloom\BlingNfe\Model;

use Eloom\BlingNfe\Api\Data\NfeInterface;
use Eloom\BlingNfe\Api\Data\NfeInterfaceFactory;
use Eloom\BlingNfe\Api\Data\NfeSearchResultsInterfaceFactory;
use Eloom\BlingNfe\Api\NfeRepositoryInterface;
use Eloom\BlingNfe\Model\ResourceModel\Nfe as ResourceNfe;
use Eloom\BlingNfe\Model\ResourceModel\Nfe\CollectionFactory as NfeCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class NfeRepository implements NfeRepositoryInterface {
	
	protected $nfeCollectionFactory;
	
	protected $resource;
	
	protected $extensibleDataObjectConverter;
	
	protected $searchResultsFactory;
	
	private $storeManager;
	
	protected $dataNfeFactory;
	
	protected $dataObjectHelper;
	
	protected $dataObjectProcessor;
	
	protected $extensionAttributesJoinProcessor;
	
	private $collectionProcessor;
	
	protected $nfeFactory;
	
	
	/**
	 * @param ResourceNfe $resource
	 * @param NfeFactory $nfeFactory
	 * @param NfeInterfaceFactory $dataNfeFactory
	 * @param NfeCollectionFactory $nfeCollectionFactory
	 * @param NfeSearchResultsInterfaceFactory $searchResultsFactory
	 * @param DataObjectHelper $dataObjectHelper
	 * @param DataObjectProcessor $dataObjectProcessor
	 * @param StoreManagerInterface $storeManager
	 * @param CollectionProcessorInterface $collectionProcessor
	 * @param JoinProcessorInterface $extensionAttributesJoinProcessor
	 * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
	 */
	public function __construct(ResourceNfe                      $resource,
	                            NfeFactory                       $nfeFactory,
	                            NfeInterfaceFactory              $dataNfeFactory,
	                            NfeCollectionFactory             $nfeCollectionFactory,
	                            NfeSearchResultsInterfaceFactory $searchResultsFactory,
	                            DataObjectHelper                 $dataObjectHelper,
	                            DataObjectProcessor              $dataObjectProcessor,
	                            StoreManagerInterface            $storeManager,
	                            CollectionProcessorInterface     $collectionProcessor,
	                            JoinProcessorInterface           $extensionAttributesJoinProcessor,
	                            ExtensibleDataObjectConverter    $extensibleDataObjectConverter) {
		
		$this->resource = $resource;
		$this->nfeFactory = $nfeFactory;
		$this->nfeCollectionFactory = $nfeCollectionFactory;
		$this->searchResultsFactory = $searchResultsFactory;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->dataNfeFactory = $dataNfeFactory;
		$this->dataObjectProcessor = $dataObjectProcessor;
		$this->storeManager = $storeManager;
		$this->collectionProcessor = $collectionProcessor;
		$this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
		$this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function save(NfeInterface $nfe) {
		/* if (empty($nfe->getStoreId())) {
				$storeId = $this->storeManager->getStore()->getId();
				$nfe->setStoreId($storeId);
		} */
		
		$nfeData = $this->extensibleDataObjectConverter->toNestedArray($nfe, [], NfeInterface::class);
		
		$nfeModel = $this->nfeFactory->create()->setData($nfeData);
		
		try {
			$this->resource->save($nfeModel);
		} catch (\Exception $exception) {
			throw new CouldNotSaveException(__('Could not save the nfe: %1', $exception->getMessage()));
		}
		
		return $nfeModel->getDataModel();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get($nfeId) {
		$nfe = $this->nfeFactory->create();
		$this->resource->load($nfe, $nfeId);
		if (!$nfe->getId()) {
			throw new NoSuchEntityException(__('Nfe with id "%1" does not exist.', $nfeId));
		}
		return $nfe->getDataModel();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria) {
		$collection = $this->nfeCollectionFactory->create();
		
		$this->extensionAttributesJoinProcessor->process($collection, NfeInterface::class);
		
		$this->collectionProcessor->process($criteria, $collection);
		
		$searchResults = $this->searchResultsFactory->create();
		$searchResults->setSearchCriteria($criteria);
		
		$items = [];
		foreach ($collection as $model) {
			$items[] = $model->getDataModel();
		}
		
		$searchResults->setItems($items);
		$searchResults->setTotalCount($collection->getSize());
		
		return $searchResults;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function delete(NfeInterface $nfe) {
		try {
			$nfeModel = $this->nfeFactory->create();
			$this->resource->load($nfeModel, $nfe->getNfeId());
			$this->resource->delete($nfeModel);
		} catch (\Exception $exception) {
			throw new CouldNotDeleteException(__('Could not delete the Nfe: %1', $exception->getMessage()));
		}
		
		return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function deleteById($nfeId) {
		return $this->delete($this->get($nfeId));
	}
}