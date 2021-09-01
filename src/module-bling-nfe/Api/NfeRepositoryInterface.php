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

namespace Eloom\BlingNfe\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface NfeRepositoryInterface {
	
	/**
	 * Save Nfe
	 * @param \Eloom\BlingNfe\Api\Data\NfeInterface $nfe
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function save(
		\Eloom\BlingNfe\Api\Data\NfeInterface $nfe
	);
	
	/**
	 * Retrieve Nfe
	 * @param string $nfeId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function get($nfeId);
	
	/**
	 * Retrieve Nfe matching the specified criteria.
	 * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
	 * @return \Eloom\BlingNfe\Api\Data\NfeSearchResultsInterface
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getList(
		\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
	);
	
	/**
	 * Delete Nfe
	 * @param \Eloom\BlingNfe\Api\Data\NfeInterface $nfe
	 * @return bool true on success
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function delete(
		\Eloom\BlingNfe\Api\Data\NfeInterface $nfe
	);
	
	/**
	 * Delete Nfe by ID
	 * @param string $nfeId
	 * @return bool true on success
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function deleteById($nfeId);
}

