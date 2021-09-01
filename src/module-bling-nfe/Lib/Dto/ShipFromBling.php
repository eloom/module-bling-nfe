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

class ShipFromBling {
	
	private $method;
	
	private $code;
	
	private $tracker;
	
	/**
	 * @param $method
	 * @param $code
	 * @param $tracker
	 */
	public function __construct($method, $code, $tracker) {
		$this->method = $method;
		$this->code = $code;
		$this->tracker = $tracker;
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
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * @return mixed
	 */
	public function getTracker() {
		return $this->tracker;
	}
	
	public function isTrackerFromNfeNumber() {
		return $this->tracker == 'nfe';
	}
}