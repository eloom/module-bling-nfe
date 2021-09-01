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

namespace Eloom\BlingNfe\Controller\Adminhtml\Nfe;

use Eloom\BlingNfe\Helper\Data as Helper;
use Eloom\BlingNfe\Lib\Builder\Request\NFe\CreateNFeBuilder as CreateNFeRequestBuilder;
use Eloom\BlingNfe\Lib\Builder\Response\NFe\CreateNFeBuilder as CreateNFeRespondeBuilder;
use Eloom\BlingNfe\Service\NfeService;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;


class Index extends \Magento\Sales\Controller\Adminhtml\Order {
	
	/**
	 * @var LoggerInterface
	 */
	protected $logger;
	
	/**
	 * @var ManagerInterface
	 */
	protected $messageManager;
	
	/**
	 * @var NfeService
	 */
	protected $nfeService;
	
	/**
	 * @var CreateNFeRequestBuilder
	 */
	protected $createNfeRequestBuilder;
	
	/**
	 * @var CreateNFeRespondeBuilder
	 */
	protected $createNfeResponseBuilder;
	
	private $helper;
	
	public function __construct(Context                  $context,
	                            Registry                 $coreRegistry,
	                            FileFactory              $fileFactory,
	                            InlineInterface          $translateInline,
	                            PageFactory              $resultPageFactory,
	                            JsonFactory              $resultJsonFactory,
	                            LayoutFactory            $resultLayoutFactory,
	                            RawFactory               $resultRawFactory,
	                            OrderManagementInterface $orderManagement,
	                            OrderRepositoryInterface $orderRepository,
	                            LoggerInterface          $logger,
	                            ManagerInterface         $messageManager,
	                            NfeService               $nfeService,
	                            CreateNFeRequestBuilder  $createNfeRequestBuilder,
	                            CreateNFeRespondeBuilder $createNfeResponseBuilder,
	                            Helper                   $helper) {
		$this->messageManager = $messageManager;
		$this->nfeService = $nfeService;
		$this->createNfeRequestBuilder = $createNfeRequestBuilder;
		$this->createNfeResponseBuilder = $createNfeResponseBuilder;
		$this->helper = $helper;
		
		parent::__construct(
			$context,
			$coreRegistry,
			$fileFactory,
			$translateInline,
			$resultPageFactory,
			$resultJsonFactory,
			$resultLayoutFactory,
			$resultRawFactory,
			$orderManagement,
			$orderRepository,
			$logger
		);
	}
	
	public function execute() {
		$order = $this->_initOrder();
		if ($order) {
			try {
				if (!$this->helper->isActiveAR($order->getStoreId())) {
					throw new \Exception("Please enable NF-e / AR generation.");
				}
				$this->logger->info('NF-e for Order Id ' . $order->getId());
				
				$orderNfeRequest = $this->createNfeRequestBuilder->build($order);
				$json = $this->nfeService->toXml($orderNfeRequest)->create();
				$orderNfeResponse = $this->createNfeResponseBuilder->build($json);
				
				if ($orderNfeResponse->hasErrors()) {
					foreach ($orderNfeResponse->getErrors() as $error) {
						$this->messageManager->addNotice(sprintf(__("Order %s - Error %s - %s"), $order->getIncrementId(), $error->getCode(), $error->getMessage()));
					}
				}
				foreach ($orderNfeResponse->getNfes() as $nfe) {
					$this->messageManager->addSuccess(sprintf(__("Order %s - Successfully generated NF-e %s."), $order->getIncrementId(), $nfe->getNumber()));
				}
				
				$this->_eventManager->dispatch(
					'eloom_blingnfe_admin_sales_order_nfe_save',
					[
						'store_id' => $order->getStoreId(),
						'order_id' => $order->getId(),
						'order_status' => $order->getStatus(),
						'nfe' => $orderNfeResponse
					]
				);
			} catch (\Exception $e) {
				$this->messageManager->addNotice(sprintf(__($e->getMessage()), $order->getIncrementId()));
				$this->logger->critical($e);
			}
		}
		
		return $this->resultRedirectFactory->create()->setPath('sales/order/view', ['order_id' => $order->getId()]);
	}
}