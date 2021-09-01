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

use Magento\Framework\Api\ExtensibleDataInterface;

interface NfeInterface extends ExtensibleDataInterface {
	
	const NFE_ID = 'nfe_id';
	const CREATED_AT = 'created_at';
	const DANFE_URL = 'danfe_url';
	const BLING_ID = 'bling_id';
	const TRACKING_NUMBER = 'tracking_number';
	const ORDER_ID = 'order_id';
	const STATUS = 'status';
	const ACCESS_KEY = 'access_key';
	const BLING_NUMBER = 'bling_number';
	
	/**
	 * Get nfe_id
	 * @return string|null
	 */
	public function getNfeId();
	
	/**
	 * Set nfe_id
	 * @param string $nfeId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setNfeId($nfeId);
	
	/**
	 * Get order_id
	 * @return string|null
	 */
	public function getOrderId();
	
	/**
	 * Set order_id
	 * @param string $orderId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setOrderId($orderId);
	
	/**
	 * Retrieve existing extension attributes object or create a new one.
	 * @return \Eloom\BlingNfe\Api\Data\NfeExtensionInterface|null
	 */
	public function getExtensionAttributes();
	
	/**
	 * Set an extension attributes object.
	 * @param \Eloom\BlingNfe\Api\Data\NfeExtensionInterface $extensionAttributes
	 * @return $this
	 */
	public function setExtensionAttributes(
		\Eloom\BlingNfe\Api\Data\NfeExtensionInterface $extensionAttributes
	);
	
	/**
	 * Get bling_number
	 * @return string|null
	 */
	public function getBlingNumber();
	
	/**
	 * Set bling_number
	 * @param string $blingNumber
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setBlingNumber($blingNumber);
	
	/**
	 * Get tracking_number
	 * @return string|null
	 */
	public function getTrackingNumber();
	
	/**
	 * Set tracking_number
	 * @param string $trackingNumber
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setTrackingNumber($trackingNumber);
	
	/**
	 * Get bling_id
	 * @return string|null
	 */
	public function getBlingId();
	
	/**
	 * Set bling_id
	 * @param string $blingId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setBlingId($blingId);
	
	/**
	 * Get access_key
	 * @return string|null
	 */
	public function getAccessKey();
	
	/**
	 * Set access_key
	 * @param string $accessKey
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setAccessKey($accessKey);
	
	/**
	 * Get status
	 * @return string|null
	 */
	public function getStatus();
	
	/**
	 * Set status
	 * @param string $status
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setStatus($status);
	
	/**
	 * Get created_at
	 * @return string|null
	 */
	public function getCreatedAt();
	
	/**
	 * Set created_at
	 * @param string $createdAt
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setCreatedAt($createdAt);
	
	/**
	 * Get danfe_url
	 * @return string|null
	 */
	public function getDanfeUrl();
	
	/**
	 * Set danfe_url
	 * @param string $danfeUrl
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setDanfeUrl($danfeUrl);
}