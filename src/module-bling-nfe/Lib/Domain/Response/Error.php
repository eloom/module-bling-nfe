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

class Error {
	
	private $removalPassive = [14];
	
	private $code;
	
	private $message;
	
	/**
	 * @param $code
	 * @param $message
	 */
	public function __construct($code, $message) {
		$this->code = $code;
		$this->message = $message;
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
	public function setCode($code): void {
		$this->code = $code;
	}
	
	/**
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}
	
	/**
	 * @param mixed $message
	 */
	public function setMessage($message): void {
		$this->message = $message;
	}
	
	public function canRemove(): bool {
		if (in_array($this->code, $this->removalPassive)) {
			return true;
		}
		
		return false;
	}
}