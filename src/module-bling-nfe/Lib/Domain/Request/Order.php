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

use Eloom\BlingNfe\Lib\Enumeration\Order\Finality as FinalityEnum;
use Eloom\BlingNfe\Lib\Enumeration\Order\Type as TypeEnum;

class Order {
	
	private $storeId;
	
	/**
	 * @var TypeEnum
	 */
	private $type;
	
	/**
	 * @var FinalityEnum
	 */
	private $finality;
	
	/**
	 * Número do pedido na loja virtual
	 *
	 * @var string
	 */
	private $id;
	
	private $nf;
	
	private $natureOperation;
	
	private $dateOperation;
	
	/**
	 * @var float
	 */
	private $shippingAmount = 0.0;
	
	/**
	 * @var float
	 */
	private $discountAmount = 0.0;
	
	/**
	 * @var float
	 */
	private $interestAmount = 0.0;
	
	/**
	 * @var array Item
	 */
	private $items = [];
	
	/**
	 * @var Client
	 */
	private $client;
	
	/**
	 * @var Transport
	 */
	private $transport;
	
	/**
	 * @var array Installment
	 */
	private $installments = [];
	
	/**
	 * @return TypeEnum
	 */
	public function getType(): TypeEnum {
		return $this->type;
	}
	
	/**
	 * @param TypeEnum $type
	 */
	public function setType(TypeEnum $type) {
		$this->type = $type;
		
		return $this;
	}
	
	/**
	 * @return FinalityEnum
	 */
	public function getFinality(): FinalityEnum {
		return $this->finality;
	}
	
	/**
	 * @param FinalityEnum $finality
	 */
	public function setFinality(FinalityEnum $finality) {
		$this->finality = $finality;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @param string $id
	 */
	public function setId(string $id) {
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNf() {
		return $this->nf;
	}
	
	/**
	 * @param mixed $nf
	 */
	public function setNf($nf) {
		$this->nf = $nf;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNatureOperation() {
		return $this->natureOperation;
	}
	
	/**
	 * @param mixed $natureOperation
	 */
	public function setNatureOperation($natureOperation) {
		$this->natureOperation = $natureOperation;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDateOperation() {
		return $this->dateOperation;
	}
	
	/**
	 * @param mixed $dateOperation
	 */
	public function setDateOperation($dateOperation) {
		$this->dateOperation = $dateOperation;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getShippingAmount() {
		return strval(round($this->shippingAmount, 2));
	}
	
	/**
	 * @param mixed $shippingAmount
	 */
	public function setShippingAmount($shippingAmount) {
		$this->shippingAmount = $shippingAmount;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDiscountAmount() {
		return strval(round($this->discountAmount, 2));
	}
	
	/**
	 * @param mixed $discountAmount
	 */
	public function setDiscountAmount($discountAmount) {
		$this->discountAmount = $discountAmount;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getInterestAmount() {
		return strval(round($this->interestAmount, 2));
	}
	
	/**
	 * @param mixed $interestAmount
	 */
	public function setInterestAmount($interestAmount) {
		$this->interestAmount = $interestAmount;
		
		return $this;
	}
	
	public function items(): array {
		return $this->items;
	}
	
	/**
	 * @return Item
	 */
	public function addItem() {
		$this->items[] = new Item();
		
		return end($this->items);
	}
	
	public function installments(): array {
		return $this->installments;
	}
	
	public function client(): Client {
		if (!$this->client) {
			$this->client = new Client();
		}
		
		return $this->client;
	}
	
	public function transport(): Transport {
		if (!$this->transport) {
			$this->transport = new Transport();
		}
		
		return $this->transport;
	}
	
	public function hasTransport(): bool {
		return null != $this->transport;
	}
	
	/**
	 * @param array $installments
	 */
	public function setInstallments(array $installments): void {
		$this->installments = $installments;
	}
	
	/**
	 * @return mixed
	 */
	public function getStoreId() {
		return $this->storeId;
	}
	
	/**
	 * @param mixed $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
		
		return $this;
	}
}