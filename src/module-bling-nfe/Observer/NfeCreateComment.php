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

use Eloom\BlingNfe\Helper\Data as Helper;
use Eloom\BlingNfe\Lib\StoreApi;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class NfeCreateComment implements ObserverInterface {
	
	/**
	 * @var Helper
	 */
	private $helper;
	
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	
	public function __construct(Helper          $helper,
	                            LoggerInterface $logger) {
		$this->helper = $helper;
		$this->logger = $logger;
	}
	
	public function execute(Observer $observer) {
		$event = $observer->getEvent();
		$storeId = $event->getStoreId();
		$orderId = $event->getOrderId();
		$orderStatus = $event->getOrderStatus();
		$nfe = $event->getNfe();
		
		$data = [
			'statusHistory' => [
				'comment' => sprintf(__('NF-e %s generated.'), $nfe),
				'is_customer_notified' => 1,
				'is_visible_on_front' => 1,
				'status' => $orderStatus
			]
		];
		
		$storeApi = new StoreApi($storeId);
		$storeApi->orders()->comments($orderId, $data);
	}
}