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

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\ShipmentFactory;
use Psr\Log\LoggerInterface;

class ShipmentCreate implements ObserverInterface {
	
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	
	/**
	 * Core event manager proxy
	 *
	 * @var ManagerInterface
	 */
	protected $eventManager = null;
	
	/**
	 * @var OrderRepositoryInterface
	 */
	protected $orderRepository;
	
	/**
	 * The ShipmentFactory is used to create a new Shipment.
	 *
	 * @var ShipmentFactory
	 */
	protected $shipmentFactory;
	
	public function __construct(LoggerInterface          $logger,
	                            ManagerInterface         $eventManager,
	                            OrderRepositoryInterface $orderRepository,
	                            ShipmentFactory          $shipmentFactory) {
		
		$this->logger = $logger;
		$this->eventManager = $eventManager;
		$this->orderRepository = $orderRepository;
		$this->shipmentFactory = $shipmentFactory;
	}
	
	public function execute(Observer $observer) {
		$event = $observer->getEvent();
		$storeId = $event->getStoreId();
		$orderId = $event->getOrderId();
		$canInvoice = $event->getCanInvoice();
		
		if ($canInvoice) {
			$this->eventManager->dispatch('eloom_blingnfe_admin_invoice_create', [
					'store_id' => $storeId,
					'order_id' => $orderId
				]
			);
		}
		
		try {
			$order = $this->orderRepository->get($orderId);
			if ($order->canShip()) {
				$track = array(array(
					'carrier_code' => $event->getCarrierCode(),
					'title' => $event->getCarrierCode(),
					'number' => $event->getTrackNumber(),
				));
				
				$items = [];
				foreach ($order->getAllItems() as $item) {
					$items[$item->getItemId()] = $item->getQtyOrdered();
				}
				
				$shipment = $this->shipmentFactory->create($order, $items, $track);
				
				if ($shipment->getTotalQty()) {
					$shipment->register()->save();
					
					$order->addStatusToHistory(Order::STATE_COMPLETE, __("New Shipment for Order #%1.", $orderId))
						->setIsCustomerNotified(true)
						->save();
				}
			}
		} catch (\Exception $e) {
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
		}
	}
}