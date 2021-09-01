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

class Order {
	
	private $nfes = [];
	
	private $errors = [];
	
	public function addNfe(): Nfe {
		$this->nfes[] = new NFe();
		
		return end($this->nfes);
	}
	
	public function getLastNFe(): Nfe {
		return end($this->nfes);
	}
	
	public function addError($code, $message) {
		$this->errors[] = new Error($code, $message);
		
		return end($this->errors);
	}
	
	public function hasErrors(): bool {
		return (count($this->errors) > 0);
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function getNfes() {
		return $this->nfes;
	}
}