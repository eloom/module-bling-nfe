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

namespace Eloom\BlingNfe\Model\ResourceModel\Nfe;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
	
	/**
	 * @var string
	 */
	protected $_idFieldName = 'nfe_id';
	
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct() {
		$this->_init(
			\Eloom\BlingNfe\Model\Nfe::class,
			\Eloom\BlingNfe\Model\ResourceModel\Nfe::class
		);
	}
}