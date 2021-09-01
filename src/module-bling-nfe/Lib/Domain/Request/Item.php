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

use Eloom\BlingNfe\Lib\Enumeration\Item\Type as TypeEnum;
use Eloom\BlingNfe\Lib\Enumeration\Item\UnitType as UnitTypeEnum;

class Item {
	
	private $code;
	
	private $description;
	
	/**
	 * @var UnitTypeEnum
	 */
	private $um;
	
	private $qty;
	
	private $price;
	
	/**
	 * @var TypeEnum
	 */
	private $type;
	
	private $ncm;
	
	private $cest;
	
	private $source;
	
	private $gtin;
	
	private $additionalInformation;
	
	/**
	 * @return mixed
	 */
	public function getAdditionalInformation() {
		return $this->additionalInformation;
	}
	
	/**
	 * @param mixed $additionalInformation
	 */
	public function setAdditionalInformation($additionalInformation) {
		$this->additionalInformation = $additionalInformation;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @param mixed $code
	 */
	public function setCode($code) {
		$this->code = $code;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = substr($description, 0, 255);
		
		return $this;
	}
	
	/**
	 * @return UnitTypeEnum
	 */
	public function getUm(): UnitTypeEnum {
		return $this->um;
	}
	
	/**
	 * @param string $um
	 */
	public function setUm($um) {
		$this->um = UnitTypeEnum::memberByKey($um);
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getQty() {
		return $this->qty;
	}
	
	/**
	 * @param mixed $qty
	 */
	public function setQty($qty) {
		$this->qty = $qty;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPrice() {
		return $this->price;
	}
	
	/**
	 * @param mixed $price
	 */
	public function setPrice($price) {
		$this->price = round($price, 2);
		
		return $this;
	}
	
	/**
	 * @return TypeEnum
	 */
	public function getType(): TypeEnum {
		return $this->type;
	}
	
	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = TypeEnum::memberByKey($type);
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNcm() {
		return $this->ncm;
	}
	
	/**
	 * @param mixed $ncm
	 */
	public function setNcm($ncm) {
		$this->ncm = $ncm;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCest() {
		return $this->cest;
	}
	
	/**
	 * @param mixed $cest
	 */
	public function setCest($cest) {
		$this->cest = $cest;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getSource() {
		return $this->source;
	}
	
	/**
	 * @param mixed $source
	 */
	public function setSource($source) {
		$this->source = $source;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getGtin() {
		return $this->gtin;
	}
	
	/**
	 * @param mixed $gtin
	 */
	public function setGtin($gtin) {
		$this->gtin = $gtin;
		
		return $this;
	}
}