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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class OrderHistoryCreate implements ObserverInterface {
	
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	
	/**
	 * @var OrderRepositoryInterface
	 */
	protected $orderRepository;
	
	public function __construct(LoggerInterface          $logger,
	                            OrderRepositoryInterface $orderRepository) {
		$this->logger = $logger;
		$this->orderRepository = $orderRepository;
	}
	
	public function execute(Observer $observer) {
		try {
			$event = $observer->getEvent();
			$orderId = $event->getOrderId();
			$orderStatus = $event->getOrderStatus();
			$nfe = $event->getNfe();
			
			$order = $this->orderRepository->get($orderId);
			$order->addStatusToHistory($orderStatus, sprintf(__('New NF-e %s for Order #%s.'), $nfe, $orderId))
				->setIsCustomerNotified(true)
				->save();
		} catch (\Exception $e) {
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
		}
	}
}