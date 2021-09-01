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

namespace Eloom\BlingNfe\Lib\Domain\Response;

class NFe {
	
	private $id;
	
	private $number;
	
	private $accessKey;
	
	private $status;
	
	private $url;
	
	private $trackers = [];
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
		
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
	 * @return array
	 */
	public function getTrackers(): array {
		return $this->trackers;
	}
	
	/**
	 * @return string
	 */
	public function getTracker($index = 0): string {
		return $this->trackers[$index];
	}
	
	/**
	 * @param array $trackers
	 */
	public function setTrackers(array $trackers) {
		$this->trackers = $trackers;
		
		return $this;
	}
	
	/**
	 * @param string $tracker
	 */
	public function addTracker(string $tracker) {
		$this->trackers[] = $tracker;
		
		return $this;
	}
	
	public function hasTracker(): bool {
		return (count($this->trackers) > 0);
	}
	
	/**
	 * @return mixed
	 */
	public function getAccessKey() {
		return $this->accessKey;
	}
	
	/**
	 * @param mixed $accessKey
	 */
	public function setAccessKey($accessKey) {
		$this->accessKey = $accessKey;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status
	 */
	public function setStatus($status) {
		$this->status = $status;
		
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @param mixed $url
	 */
	public function setUrl($url) {
		$this->url = $url;
		
		return $this;
	}
}