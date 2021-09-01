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

class NfeCreateShip implements ObserverInterface {
	
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
		$canInvoice = $event->getCanInvoice();
		
		$trackNumber = $event->getTrackNumber();
		$carrierTitle = $event->getCarrierTitle();
		$carrierCode = $event->getCarrierCode();
		
		$storeApi = new StoreApi($storeId);
		
		if ($canInvoice) {
			$data = [
				'comment' => [
					'comment' => __('Automatically generated invoice when syncing with Bling.'),
					'is_visible_on_front' => 0
				],
				'capture' => true,
				'notify' => true,
				'items' => $event->getItems()
			];
			
			$storeApi->orders()->invoice($orderId, $data);
		}
		
		$data = [
			'appendComment' => true,
			'comment' => [
				'comment' => sprintf(__('Ship locator %s'), $trackNumber),
				'is_visible_on_front' => 1
			],
			'items' => $event->getItems(),
			'notify' => true,
			'tracks' => [
				[
					'track_number' => $trackNumber,
					'title' => $carrierTitle,
					'carrier_code' => $carrierCode
				]
			]
		];
		
		$storeApi->orders()->ship($orderId, $data);
	}
}