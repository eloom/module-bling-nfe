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

namespace Eloom\BlingNfe\Observer;

use Eloom\BlingNfe\Api\Data\NfeInterface;
use Eloom\BlingNfe\Api\NfeRepositoryInterface;
use Eloom\BlingNfe\Helper\Data as Helper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class NFeSave implements ObserverInterface {
	
	/**
	 * @var NfeRepositoryInterface
	 */
	private $nfeRepository;
	
	/**
	 * @var Helper
	 */
	private $helper;
	
	/**
	 * Core event manager proxy
	 *
	 * @var ManagerInterface
	 */
	protected $eventManager = null;
	
	public function __construct(NfeRepositoryInterface $nfeRepository,
	                            Helper                 $helper,
	                            ManagerInterface       $eventManager) {
		$this->nfeRepository = $nfeRepository;
		$this->helper = $helper;
		$this->eventManager = $eventManager;
	}
	
	/**
	 * @inheritdoc
	 */
	public function execute(Observer $observer) {
		$nfeResponse = $observer->getEvent()->getNfe();
		$storeId = $observer->getEvent()->getStoreId();
		$orderId = $observer->getEvent()->getOrderId();
		$orderStatus = $observer->getEvent()->getOrderStatus();
		$dto = $this->helper->getOrderStatusNfeAR($orderStatus, $storeId);
		
		/**
		 * Persist
		 */
		foreach ($nfeResponse->getNfes() as $nfe) {
			$nfeModel = ObjectManager::getInstance()->get(NfeInterface::class);
			$nfeModel->setOrderId($orderId);
			$nfeModel->setBlingId($nfe->getId());
			$nfeModel->setBlingNumber($nfe->getNumber());
			$nfeModel->setTrackingNumber('WAITING');
			$nfeModel->setAccessKey('WAITING');
			
			if ($nfe->hasTracker()) {
				$nfeModel->setTrackingNumber($nfe->getTracker(0));
			}
			$this->nfeRepository->save($nfeModel);
			
			$this->eventManager->dispatch('eloom_blingnfe_admin_sales_order_nfe_comment_create', [
					'store_id' => $storeId,
					'order_id' => $orderId,
					'order_status' => $dto->getFinal(),
					'nfe' => $nfe->getNumber()
				]
			);
		}
		
		// disparar evento de tracking
	}
}