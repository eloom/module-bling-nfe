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

namespace Eloom\BlingNfe\Lib\Dto;

class ShipToBling {
	
	private $method;
	
	private $carrier;
	
	private $service;
	
	/**
	 * @param $method
	 * @param $carrier
	 * @param $service
	 */
	public function __construct($method, $carrier, $service) {
		$this->method = $method;
		$this->carrier = $carrier;
		$this->service = $service;
	}
	
	/**
	 * @return mixed
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * @return mixed
	 */
	public function getCarrier() {
		return $this->carrier;
	}
	
	/**
	 * @return mixed
	 */
	public function getService() {
		return $this->service;
	}
}