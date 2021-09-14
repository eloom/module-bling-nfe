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
use Magento\Sales\Api\Data\ShipmentTrackInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipmentRepositoryInterface;
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
	 * @var ShipmentRepositoryInterface
	 */
	private $shipmentRepository;
	
	/**
	 * @var ShipmentTrackInterfaceFactory
	 */
	private $trackFactory;
	
	/**
	 * @var OrderRepositoryInterface
	 */
	protected $orderRepository;
	
	public function __construct(LoggerInterface               $logger,
	                            ManagerInterface              $eventManager,
	                            ShipmentRepositoryInterface   $shipmentRepository,
	                            ShipmentTrackInterfaceFactory $trackFactory,
	                            OrderRepositoryInterface      $orderRepository) {
		$this->logger = $logger;
		$this->eventManager = $eventManager;
		$this->shipmentRepository = $shipmentRepository;
		$this->trackFactory = $trackFactory;
		$this->orderRepository = $orderRepository;
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
				$this->logger->info(sprintf("New Shipment for order %s.", $orderId));
				
				$shipment = $this->shipmentFactory->create($order);
				
				$track = $this->trackFactory->create()
					->setNumber($event->getTrackNumber())
					->setCarrierCode($event->getCarrierCode())
					->setTitle($event->getCarrierTitle());
				
				$shipment->addTrack($track);
				$this->shipmentRepository->save($shipment);
				
				$order->addStatusHistoryComment(__("New Shipment for Order #%1.", $orderId))
					->setIsCustomerNotified(true)
					->save();
			}
		} catch (NoSuchEntityException $e) {
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
		}
	}
}