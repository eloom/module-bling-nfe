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

namespace Eloom\BlingNfe\Model\ResourceModel\Nfe\Grid;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

class Collection extends \Eloom\BlingNfe\Model\ResourceModel\Nfe\Collection implements SearchResultInterface {
	
	/**
	 * @var AggregationInterface
	 */
	protected $aggregations;
	
	public function __construct(EntityFactoryInterface $entityFactory,
	                            LoggerInterface        $logger,
	                            FetchStrategyInterface $fetchStrategy,
	                            ManagerInterface       $eventManager,
	                            StoreManagerInterface  $storeManager,
	                                                   $mainTable,
	                                                   $eventPrefix,
	                                                   $eventObject,
	                                                   $resourceModel,
	                                                   $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
	                                                   $connection = null,
	                            AbstractDb             $resource = null) {
		
		parent::__construct(
			$entityFactory,
			$logger,
			$fetchStrategy,
			$eventManager,
			$connection,
			$resource
		);
		$this->_eventPrefix = $eventPrefix;
		$this->_eventObject = $eventObject;
		$this->_init($model, $resourceModel);
		$this->setMainTable($mainTable);
	}
	
	protected function _initSelect() {
		parent::_initSelect();
		
		$this->getSelect()
			->joinLeft(
				['so' => $this->getTable('sales_order')],
				'so.entity_id = main_table.order_id',
				[]
			);
	}
	
	/**
	 * @return AggregationInterface
	 */
	public function getAggregations() {
		return $this->aggregations;
	}
	
	/**
	 * @param AggregationInterface $aggregations
	 * @return $this
	 */
	public function setAggregations($aggregations) {
		$this->aggregations = $aggregations;
	}
	
	/**
	 * Get search criteria.
	 *
	 * @return SearchCriteriaInterface|null
	 */
	public function getSearchCriteria() {
		return null;
	}
	
	/**
	 * Set search criteria.
	 *
	 * @param SearchCriteriaInterface $searchCriteria
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null) {
		return $this;
	}
	
	/**
	 * Get total count.
	 *
	 * @return int
	 */
	public function getTotalCount() {
		return $this->getSize();
	}
	
	/**
	 * Set total count.
	 *
	 * @param int $totalCount
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setTotalCount($totalCount) {
		return $this;
	}
	
	/**
	 * Set items list.
	 *
	 * @param ExtensibleDataInterface[] $items
	 * @return $this
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function setItems(array $items = null) {
		return $this;
	}
}
