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

namespace Eloom\BlingNfe\Cron;

use Eloom\Bling\Helper\Data as BlingHelper;
use Eloom\BlingNfe\Api\NfeRepositoryInterface;
use Eloom\BlingNfe\Helper\Data as Helper;
use Eloom\BlingNfe\Lib\BlingApi;
use Eloom\BlingNfe\Lib\Builder\Response\NFe\GetNFeBuilder as GetNFeRespondeBuilder;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;

class ShipSyncronize {
	
	private $nfeRepository;
	
	private $searchCriteriaBuilder;
	
	private $logger;
	
	private $helper;
	
	private $orderRepository;
	
	/**
	 * Core event manager proxy
	 *
	 * @var ManagerInterface
	 */
	protected $eventManager = null;
	
	public function __construct(LoggerInterface          $logger,
	                            NfeRepositoryInterface   $nfeRepository,
	                            SearchCriteriaBuilder    $searchCriteriaBuilder,
	                            Helper                   $helper,
	                            OrderRepositoryInterface $orderRepository,
	                            ManagerInterface         $eventManager) {
		$this->logger = $logger;
		$this->nfeRepository = $nfeRepository;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
		$this->helper = $helper;
		$this->orderRepository = $orderRepository;
		$this->eventManager = $eventManager;
	}
	
	public function execute() {
		$searchCriteria = $this->searchCriteriaBuilder
			->addFilter('tracking_number', 'WAITING', 'eq')
			->addFilter('created_at', date('Y-m-d H:i:s', strtotime('-3 day', time())), 'gt')
			->create();
		
		$collection = $this->nfeRepository->getList($searchCriteria);
		$list = $collection->getItems();
		
		if (count($list)) {
			$helperBling = ObjectManager::getInstance()->get(BlingHelper::class);
			$getNFeRespondeBuilder = ObjectManager::getInstance()->get(GetNFeRespondeBuilder::class);
			
			foreach ($list as $model) {
				try {
					$this->logger->info(sprintf("%s - Synchronizing NF-e Tracker %s", __METHOD__, $model->getBlingNumber()));
					$orderId = $model->getOrderId();
					$order = $this->orderRepository->get($orderId);
					
					$storeId = $order->getStoreId();
					$statusToShip = $this->helper->getOrderStatusToShip($storeId);
					
					if ($statusToShip != $order->getStatus()) {
						continue;
					}
					$apiKey = $helperBling->getApiKey($storeId);
					$serie = $this->helper->getSerieAR($storeId);
					
					$blingApi = new BlingApi($apiKey);
					$json = $blingApi->nfes()->find($model->getBlingNumber(), $serie);
					
					$shippingDto = $this->helper->getShipFromBling($order->getShippingMethod(), $storeId);
					
					$orderNfeResponse = $getNFeRespondeBuilder->build($json, $shippingDto);
					
					if ($orderNfeResponse->hasErrors()) {
						foreach ($orderNfeResponse->getErrors() as $error) {
							$this->logger->error(sprintf(__("Order %s - Error %s - %s"), $order->getIncrementId(), $error->getCode(), $error->getMessage()));
							
							if ($error->canRemove()) {
								$this->nfeRepository->delete($model);
							}
						}
						
						continue;
					}
					
					$nfe = $orderNfeResponse->getLastNFe();
					if ($nfe->hasTracker()) {
						$model->setTrackingNumber($nfe->getTracker(0));
						$model->setStatus($nfe->getStatus());
						$this->nfeRepository->save($model);
						
						if ($order->canShip()) {
							$items = [];
							foreach ($order->getAllItems() as $orderItem) {
								$items[] = [
									'order_item_id' => $orderItem->getItemId(),
									'qty' => $orderItem->getQtyOrdered()
								];
							}
							
							$this->eventManager->dispatch(
								'eloom_blingnfe_admin_sales_order_nfe_shipment_create',
								[
									'store_id' => $storeId,
									'order_id' => $orderId,
									'can_invoice' => $order->canInvoice(),
									'items' => $items,
									'track_number' => $model->getTrackingNumber(),
									'carrier_title' => $order->getShippingDescription(),
									'carrier_code' => $shippingDto->getCode()
								]
							);
						}
					}
				} catch (\Exception $e) {
					$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
				}
			}
		}
	}
}