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

namespace Eloom\BlingNfe\Lib\Domain\Request;

use Magento\Sales\Model\Order\Payment\Interceptor as Payment;

class PayUCreditCard implements PaymentInterface {
	
	private $payment;
	
	public function __construct(Payment $payment) {
		$this->payment = $payment;
	}
	
	public function getInstallments(): array {
		$installments = [];
		$paymentMethod = $this->payment->getMethodInstance()->getTitle();
		$createTime = $this->payment->getOrder()->getUpdatedAt();
		
		$totalInstallments = $this->payment->getAdditionalInformation('installments');
		for ($i = 0; $i < $totalInstallments; $i++) {
			$days = ($i + 1) * 30;
			$paymentDay = (new \DateTime())->setTimestamp(strtotime($createTime . " + $days day"))->format('d/m/Y');
			
			$installments[$i] = (new Installment())
				->setDays($days)
				->setDate($paymentDay)
				->setMethod($paymentMethod)
				->setTotal($this->payment->getAdditionalInformation('installmentAmount'))
				->setObservations(sprintf("%s", ucfirst($this->payment->getCcType())));
		}
		
		return $installments;
	}
}