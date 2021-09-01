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

namespace Eloom\BlingNfe\Lib\Builder\Request\NFe;

use Eloom\Bling\Lib\Exception\BlingException;
use Eloom\BlingNfe\Helper\Data as Helper;
use Eloom\BlingNfe\Lib\Domain\Request\Order;
use Eloom\BlingNfe\Lib\Enumeration\Client\PersonType as PersonTypeEnum;
use Eloom\BlingNfe\Lib\Enumeration\Order\Type as TypeEnum;
use Eloom\BlingNfe\Lib\Factory\PaymentFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Escaper;
use Magento\Sales\Api\Data\OrderInterface;

class CreateNFeBuilder {
	
	/**
	 * @var Helper
	 */
	private $helper;
	
	private $productRepository;
	
	/**
	 * Escaper
	 *
	 * @var Escaper
	 */
	private $escaper;
	
	public function __construct(Helper            $helper,
	                            ProductRepository $productRepository,
	                            Escaper           $escaper) {
		$this->helper = $helper;
		$this->productRepository = $productRepository;
		$this->escaper = $escaper;
	}
	
	/**
	 * @param OrderInterface $order
	 */
	public function build(OrderInterface $order) {
		$storeId = $order->getStoreId();
		if (!$this->helper->isAllowedToGenerateNfeAR($order->getStatus(), $storeId)) {
			throw new BlingException('Order %s - Status not allowed to generate NF-e AR.');
		}
		$serie = $this->helper->getSerieAR($storeId);
		if (empty($serie)) {
			throw new BlingException('Order %s - Status not allowed to generate NF-e AR.');
		}
		
		$shippingDto = $this->helper->getOrderStatusNfeAR($order->getStatus(), $storeId);
		$orderNfe = new Order();
		$orderNfe->setStoreId($storeId)
			->setId($order->getIncrementId())
			->setType(TypeEnum::S())
			->setNatureOperation($shippingDto->getOperation())
			->setShippingAmount($order->getBaseShippingAmount())
			->setDiscountAmount($this->getDiscountAmount($order))
			->setInterestAmount($this->getInterestAmount($order));
		
		/**
		 * Items
		 */
		foreach ($order->getAllItems() as $orderItem) {
			if ('configurable' == $orderItem->getProductType()) {
				continue;
			}
			$product = $this->productRepository->getById($orderItem->getProductId());
			
			if (!$product->hasData('nfe_ncm')) {
				throw new BlingException(sprintf(__('Product %s - NF-e NCM cannot be null.'), $product->getSku()));
			}
			if (!$product->hasData('nfe_um')) {
				throw new BlingException(sprintf(__('Product %s - NF-e unit of measurement cannot be null.'), $product->getSku()));
			}
			if (!$product->hasData('nfe_item_type')) {
				throw new BlingException(sprintf(__('Product %s - NF-e item type cannot be null.'), $product->getSku()));
			}
			if (!$product->hasData('nfe_product_source')) {
				throw new BlingException(sprintf(__('Product %s - NF-e source cannot be null.'), $product->getSku()));
			}
			
			$description = $orderItem->getName();
			$chunk = explode('|', $product->getAttributeText('nfe_um'));
			$um = preg_replace('/\W/', '', $chunk[1]);
			
			$chunk = explode('-', $product->getAttributeText('nfe_product_source'));
			$source = preg_replace('/\D/', '', $chunk[0]);
			
			$chunk = explode('|', $product->getAttributeText('nfe_item_type'));
			$itemType = preg_replace('/\W/', '', $chunk[1]);
			
			$orderNfe->addItem()
				->setCode($orderItem->getSku())
				->setDescription($this->escaper->escapeHtml(htmlentities($description)))
				->setQty($orderItem->getQtyOrdered())
				->setPrice($orderItem->getBasePrice())
				->setNcm($product->getNfeNcm())
				->setUm($um)
				->setType($itemType)
				->setSource($source)
				->setGtin($product->getNfeGtin())
				->setCest($product->getNfeCest());
		}
		
		/**
		 * Client
		 */
		$taxvat = ($order->getCustomerTaxvat() ? $order->getCustomerTaxvat() : $order->getBillingAddress()->getVatId());
		$taxvat = preg_replace('/\D/', '', $taxvat);
		$personType = PersonTypeEnum::F()->key();
		if (strlen($taxvat) == 14) {
			$personType = PersonTypeEnum::J()->key();
		}
		
		$billingAddress = $order->getBillingAddress();
		if (!$billingAddress) {
			$billingAddress = $order->getShippingAddress();
		}
		$street = $billingAddress->getStreet();
		
		$orderNfe->client()
			->setName($order->getCustomerName())
			->setPersonType($personType)
			->setTaxvat($taxvat)
			->address()
			->setEmail($billingAddress->getEmail())
			->setPhone($billingAddress->getTelephone())
			->setPlace($street[0])
			->setNumber($street[1] ?? 'NI')
			->setComplement($street[2] ?? 'NI')
			->setDistrict($street[3] ?? 'NI')
			->setCity($billingAddress->getCity())
			->setState($billingAddress->getRegionCode())
			->setPostCode($billingAddress->getPostcode());
		
		/**
		 * Transport
		 */
		if ($order->getShippingAddress()) {
			$shippingAddress = $order->getShippingAddress();
			$street = $shippingAddress->getStreet();
			
			$shippingMethod = $order->getShippingMethod();
			$shippingDto = $this->helper->getShipToBling($shippingMethod, $storeId);
			
			if (null == $shippingDto) {
				throw new BlingException('Order %s - Shipping method not found in mapping.');
			}
			
			$orderNfe->transport()
				->setName($shippingDto->getCarrier())
				->tag()
				->setName($shippingDto->getService())
				->setPlace($street[0])
				->setNumber($street[1] ?? 'NI')
				->setComplement($street[2] ?? 'NI')
				->setDistrict($street[3] ?? 'NI')
				->setCity($shippingAddress->getCity())
				->setState($shippingAddress->getRegionCode())
				->setPostCode($shippingAddress->getPostcode());;
		}
		
		/**
		 * Payment
		 */
		$paymentFactory = new PaymentFactory();
		$payment = $paymentFactory->createPayment($order->getPayment());
		if (null == $payment) {
			throw new BlingException('Order %s - Payment method not found in mapping.');
		}
		$orderNfe->setInstallments($payment->getInstallments());
		
		return $orderNfe;
	}
	
	/**
	 * Gets extra amount values for order
	 *
	 * @return float
	 */
	protected function getDiscountAmount($order) {
		$addition = 0.00;
		$discount = 0.00;
		if ($order->getBaseDiscountAmount()) {
			$discount += $order->getBaseDiscountAmount();
		}
		if ($order->getPayuBaseDiscountAmount()) {
			$discount += $order->getPayuBaseDiscountAmount();
		}
		if ($order->getBaseTaxAmount()) {
			$addition = $order->getBaseTaxAmount();
		}
		$amount = $addition + $discount;
		
		return abs(round($amount, 2));
	}
	
	protected function getInterestAmount($order) {
		$interest = 0.00;
		if ($order->getPayuBaseInterestAmount()) {
			$interest += $order->getPayuBaseInterestAmount();
		}
		
		return abs(round($interest, 2));
	}
}