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

class OrderStatus {
	
	private $initial;
	
	private $operation;
	
	private $final;
	
	/**
	 * @param $initial
	 * @param $operation
	 * @param $final
	 */
	public function __construct($initial, $operation, $final) {
		$this->initial = $initial;
		$this->operation = $operation;
		$this->final = $final;
	}
	
	/**
	 * @return mixed
	 */
	public function getInitial() {
		return $this->initial;
	}
	
	/**
	 * @return mixed
	 */
	public function getOperation() {
		return $this->operation;
	}
	
	/**
	 * @return mixed
	 */
	public function getFinal() {
		return $this->final;
	}
}