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
use Eloom\BlingNfe\Model\ResourceModel\Nfe\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Nfe extends \Magento\Framework\Model\AbstractModel {
	
	protected $nfeDataFactory;
	
	protected $dataObjectHelper;
	
	protected $_eventPrefix = 'eloom_blingnfe_nfe';
	
	/**
	 * @param Context $context
	 * @param Registry $registry
	 * @param NfeInterfaceFactory $nfeDataFactory
	 * @param DataObjectHelper $dataObjectHelper
	 * @param \Eloom\BlingNfe\Model\ResourceModel\Nfe $resource
	 * @param Collection $resourceCollection
	 * @param array $data
	 */
	public function __construct(Context                                 $context,
	                            Registry                                $registry,
	                            NfeInterfaceFactory                     $nfeDataFactory,
	                            DataObjectHelper                        $dataObjectHelper,
	                            \Eloom\BlingNfe\Model\ResourceModel\Nfe $resource,
	                            Collection                              $resourceCollection,
	                            array                                   $data = []) {
		$this->nfeDataFactory = $nfeDataFactory;
		$this->dataObjectHelper = $dataObjectHelper;
		
		parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}
	
	/**
	 * Retrieve nfe model with nfe data
	 * @return NfeInterface
	 */
	public function getDataModel() {
		$nfeData = $this->getData();
		
		$nfeDataObject = $this->nfeDataFactory->create();
		$this->dataObjectHelper->populateWithArray(
			$nfeDataObject,
			$nfeData,
			NfeInterface::class
		);
		
		return $nfeDataObject;
	}
}