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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Service\InvoiceService;
use Psr\Log\LoggerInterface;

class InvoiceCreate implements ObserverInterface {
	
	private $logger;
	
	/**
	 * @var OrderRepositoryInterface
	 */
	protected $orderRepository;
	
	protected $invoiceService;
	
	protected $invoiceSender;
	
	public function __construct(LoggerInterface          $logger,
	                            OrderRepositoryInterface $orderRepository,
	                            InvoiceService           $invoiceService,
	                            InvoiceSender            $invoiceSender,
	                            Transaction              $transaction) {
		
		$this->logger = $logger;
		$this->orderRepository = $orderRepository;
		$this->invoiceService = $invoiceService;
		$this->invoiceSender = $invoiceSender;
	}
	
	/**
	 * @inheritdoc
	 */
	public function execute(Observer $observer) {
		$orderId = $observer->getEvent()->getOrderId();
		$this->logger->info(__("New Invoice for Order #%1.", $orderId));
		
		$order = $this->orderRepository->get($orderId);
		$invoice = $this->invoiceService->prepareInvoice($order);
		if (!$invoice) {
			throw new LocalizedException(__('We can\'t save the invoice for order #%1.', $orderId));
		}
		if (!$invoice->getTotalQty()) {
			throw new LocalizedException(__('You can\'t create an invoice without products for order #%1.', $orderId));
		}
		$invoice->setRequestedCaptureCase(Invoice::CAPTURE_ONLINE);
		$invoice->register();
		$invoice->getOrder()->setCustomerNoteNotify(false);
		$invoice->getOrder()->setIsInProcess(true);
		$invoice->pay()->save();
		
		try {
			$this->invoiceSender->send($invoice);
		} catch (\Exception $e) {
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getMessage()));
			$this->logger->critical(sprintf("%s - Exception: %s", __METHOD__, $e->getTraceAsString()));
		}
		
		$order->addStatusHistoryComment(__("New Invoice for Order #%1.", $orderId))
			->setIsCustomerNotified(true)
			->save();
	}
}