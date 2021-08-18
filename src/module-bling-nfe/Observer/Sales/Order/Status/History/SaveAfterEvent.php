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

namespace Eloom\BlingNfe\Observer\Sales\Order\Status\History;

use Eloom\BlingNfe\Connection\Http\Store;
use Eloom\BlingNfe\Helper\Data as Helper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Sales\Model\Order\ShipmentDocumentFactory;
use Magento\Shipping\Model\ShipmentNotifier;
use Psr\Log\LoggerInterface;

class SaveAfterEvent implements ObserverInterface {
	
	/**
	 * @var TrackFactory
	 */
	private $trackFactory;
	
	/**
	 * @var ShipmentDocumentFactory
	 */
	private $shipmentDocumentFactory;
	
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	
	/**
	 * @var ShipmentNotifier
	 */
	private $shipmentNotifier;
	
	/**
	 * @var Helper
	 */
	private $helper;
	
	public function __construct() {
		$this->logger = ObjectManager::getInstance()->get(LoggerInterface::class);
		$this->trackFactory = ObjectManager::getInstance()->get(TrackFactory::class);
		$this->shipmentDocumentFactory = ObjectManager::getInstance()->get(ShipmentDocumentFactory::class);
		$this->shipmentNotifier = ObjectManager::getInstance()->get(ShipmentNotifier::class);
		$this->helper = ObjectManager::getInstance()->get(Helper::class);
	}
	
	public function execute(Observer $observer) {
		$url = $this->helper->getRestUrl();
		$headers = ['Authorization' => 'Bearer awexs2i36g77lkdmlchxd6qa090yfi38'];
		$data = [
			'statusHistory' => [
				'comment' => 'NF: 306 | Serie: 55 | Date: 16-08-2021 | Key: 35210804769278000189550550000003061484767549',
				'is_customer_notified' => 1,
				'is_visible_on_front' => 1
			]
		];
		
		$store = ObjectManager::getInstance()->get(Store::class);
		$promise = $store->postAsync($url . 'orders/73/comments', $data, $headers);
		
		$promise->then(
			function (ResponseInterface $res) {
				$this->logger->info($res->getStatusCode());
			},
			function (RequestException $e) {
				$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
				$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getRequest()->getMethod()));
			}
		);
		
		return;
		$history = $observer->getEvent()->getStatusHistory();
		
		$order = $history->getOrder();
		if (!$order->canShip()) {
			//throw new \Exception(__('Cannot do shipment for the order.'));
		}
		
		$ea = $history->getExtensionAttributes();
		if ($ea) {
			if ($ea->getTracks() && count($ea->getTracks())) {
				$shipment = $this->shipmentDocumentFactory->create($order);
				
				foreach ($ea->getTracks() as $track) {
					$trackData = $this->trackFactory->create()->addData([
						'carrier_code' => $track->getCarrierCode(),
						'number' => $track->getTrackNumber(),
						'title' => $track->getTitle(),
					]);
					//$shipment->addTrack($trackData);
				}
				
				//$shipment->register();
				//$shipment->getOrder()->setIsInProcess(true);
				
				try {
					//$shipment->save();
					//$shipment->getOrder()->save();
					
					//$this->$this->shipmentNotifier->notify($shipment);
				} catch (\Exception $e) {
					$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
					$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
				}
			}
		}
	}
}