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

class Transport {
	
	private $name;
	
	/**
	 * @var Tag
	 */
	private $tag;
	
	public function tag(): Tag {
		if (!$this->tag) {
			$this->tag = new Tag();
		}
		
		return $this->tag;
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
}