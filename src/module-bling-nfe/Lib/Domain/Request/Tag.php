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

class Tag {
	
	private $name;
	
	private $place;
	
	private $number;
	
	private $complement;
	
	private $district;
	
	private $postCode;
	
	private $city;
	
	private $state;
	
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
	public function getPlace() {
		return $this->place;
	}
	
	/**
	 * @param mixed $place
	 */
	public function setPlace($place) {
		$this->place = $place;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getNumber() {
		return $this->number;
	}
	
	/**
	 * @param mixed $number
	 */
	public function setNumber($number) {
		$this->number = $number;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getComplement() {
		return $this->complement;
	}
	
	/**
	 * @param mixed $complement
	 */
	public function setComplement($complement) {
		$this->complement = $complement;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDistrict() {
		return $this->district;
	}
	
	/**
	 * @param mixed $district
	 */
	public function setDistrict($district) {
		$this->district = $district;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getPostCode() {
		return $this->postCode;
	}
	
	/**
	 * @param mixed $postCode
	 */
	public function setPostCode($postCode) {
		$this->postCode = $postCode;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getCity() {
		return $this->city;
	}
	
	/**
	 * @param mixed $city
	 */
	public function setCity($city) {
		$this->city = $city;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * @param mixed $state
	 */
	public function setState($state) {
		$this->state = $state;
		
		return $this;
	}
}