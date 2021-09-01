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

namespace Eloom\BlingNfe\Lib\Factory;

use Eloom\BlingNfe\Lib\Domain\Request\PaymentInterface;
use Eloom\BlingNfe\Lib\Domain\Request\PayUBoleto;
use Eloom\BlingNfe\Lib\Domain\Request\PayUCreditCard;
use Eloom\BlingNfe\Lib\Domain\Request\PayUCreditCardVault;
use Magento\Sales\Model\Order\Payment\Interceptor as Payment;

class PaymentFactory {
	
	private $payments = [
		'eloom_payments_payu_cc' => PayUCreditCard::class,
		'eloom_payments_payu_cc_vault' => PayUCreditCardVault::class,
		'eloom_payments_payu_boleto' => PayUBoleto::class
	];
	
	/**
	 * @param Payment $payment
	 * @return PaymentInterface|null
	 */
	public function createPayment(Payment $payment): ?PaymentInterface {
		$key = $payment->getMethodInstance()->getCode();
		if (array_key_exists($key, $this->payments)) {
			return new $this->payments[$key]($payment);
		}
		
		return null;
	}
}