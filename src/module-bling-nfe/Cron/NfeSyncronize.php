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
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;

class NfeSyncronize {
	
	private $nfeRepository;
	
	private $searchCriteriaBuilder;
	
	private $logger;
	
	private $helper;
	
	private $orderRepository;
	
	public function __construct(LoggerInterface          $logger,
	                            NfeRepositoryInterface   $nfeRepository,
	                            SearchCriteriaBuilder    $searchCriteriaBuilder,
	                            Helper                   $helper,
	                            OrderRepositoryInterface $orderRepository) {
		$this->logger = $logger;
		$this->nfeRepository = $nfeRepository;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
		$this->helper = $helper;
		$this->orderRepository = $orderRepository;
	}
	
	public function execute() {
		$searchCriteria = $this->searchCriteriaBuilder
			->addFilter('access_key', 'WAITING', 'eq')
			->addFilter('created_at', date('Y-m-d H:i:s', strtotime('-1 day', time())), 'gt')
			->create();
		
		$collection = $this->nfeRepository->getList($searchCriteria);
		$list = $collection->getItems();
		
		if (count($list)) {
			$helperBling = ObjectManager::getInstance()->get(BlingHelper::class);
			$getNFeRespondeBuilder = ObjectManager::getInstance()->get(GetNFeRespondeBuilder::class);
			
			foreach ($list as $model) {
				try {
					$this->logger->info(sprintf("%s - Synchronizing NF-e %s", __METHOD__, $model->getBlingNumber()));
					
					$order = $this->orderRepository->get($model->getOrderId());
					
					$storeId = $order->getStoreId();
					$apiKey = $helperBling->getApiKey($storeId);
					$serie = $this->helper->getSerieAR($storeId);
					
					$blingApi = new BlingApi($apiKey);
					$json = $blingApi->nfes()->find($model->getBlingNumber(), $serie);
					
					$orderNfeResponse = $getNFeRespondeBuilder->build($json);
					
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
					if ($nfe->getAccessKey()) {
						$model->setAccessKey($nfe->getAccessKey());
						$model->setDanfeUrl($nfe->getUrl());
						$model->setStatus($nfe->getStatus());
						$this->nfeRepository->save($model);
						
						$comment = $this->helper->getCommentAR($storeId);
						$comment = sprintf(__($comment), $model->getBlingNumber(), $nfe->getAccessKey());
						
						$order->addStatusToHistory($order->getStatus(), $comment)
							->setIsCustomerNotified(true)
							->save();
					}
				} catch (\Exception $e) {
					$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
					$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
				}
			}
		}
	}
}