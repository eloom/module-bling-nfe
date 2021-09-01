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

class Installment {
	
	private $days;
	
	private $date;
	
	private $total;
	
	private $observations;
	
	private $method;
	
	/**
	 * @return mixed
	 */
	public function getDays() {
		return $this->days;
	}
	
	/**
	 * @param mixed $days
	 */
	public function setDays($days) {
		$this->days = $days;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getTotal() {
		return $this->total;
	}
	
	/**
	 * @param mixed $total
	 */
	public function setTotal($total) {
		$this->total = $total;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getObservations() {
		return $this->observations;
	}
	
	/**
	 * @param mixed $observations
	 */
	public function setObservations($observations) {
		$this->observations = $observations;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * @param mixed $method
	 */
	public function setMethod($method) {
		$this->method = $method;
		
		return $this;
	}
}