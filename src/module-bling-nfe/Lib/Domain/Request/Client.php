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

use Eloom\BlingNfe\Lib\Enumeration\Client\PersonType as PersonTypeEnum;

class Client {
	
	/**
	 * @var PersonTypeEnum
	 */
	private $personType;
	
	private $name;
	
	private $taxvat;
	
	private $legalNumber;
	
	/**
	 * @var Address
	 */
	private $address;
	
	public function address(): Address {
		if (!$this->address) {
			$this->address = new Address();
		}
		
		return $this->address;
	}
	
	/**
	 * @return PersonTypeEnum
	 */
	public function getPersonType(): PersonTypeEnum {
		return $this->personType;
	}
	
	/**
	 * @param string $personType
	 */
	public function setPersonType($personType) {
		$this->personType = PersonTypeEnum::memberByKey($personType);
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getTaxvat() {
		return preg_replace('/\D/', '', $this->taxvat);
	}
	
	/**
	 * @param mixed $taxvat
	 */
	public function setTaxvat($taxvat) {
		$this->taxvat = $taxvat;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getLegalNumber() {
		return $this->legalNumber;
	}
	
	/**
	 * @param mixed $legalNumber
	 */
	public function setLegalNumber($legalNumber) {
		$this->legalNumber = $legalNumber;
		
		return $this;
	}
}