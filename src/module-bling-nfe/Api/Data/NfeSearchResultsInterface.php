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

namespace Eloom\BlingNfe\Api\Data;

interface NfeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface {
	
	/**
	 * Get Nfe list.
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface[]
	 */
	public function getItems();
	
	/**
	 * Set order_id list.
	 * @param \Eloom\BlingNfe\Api\Data\NfeInterface[] $items
	 * @return $this
	 */
	public function setItems(array $items);
}

