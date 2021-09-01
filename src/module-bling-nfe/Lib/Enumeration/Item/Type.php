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

namespace Eloom\BlingNfe\Lib\Enumeration\Item;

use Eloom\Core\Lib\Enumeration\AbstractMultiton;

class Type extends AbstractMultiton {
	
	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}
	
	protected static function initializeMembers() {
		new static('P', 'Produto');
		new static('S', 'Serviço');
	}
	
	protected function __construct($key, $title) {
		parent::__construct($key);
		
		$this->title = $title;
	}
	
	private $title;
}