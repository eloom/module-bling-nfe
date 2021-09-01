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

namespace Eloom\BlingNfe\Model\Data;

use Eloom\BlingNfe\Api\Data\NfeInterface;

class Nfe extends \Magento\Framework\Api\AbstractExtensibleObject implements NfeInterface {
	
	/**
	 * Get nfe_id
	 * @return string|null
	 */
	public function getNfeId() {
		return $this->_get(self::NFE_ID);
	}
	
	/**
	 * Set nfe_id
	 * @param string $nfeId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setNfeId($nfeId) {
		return $this->setData(self::NFE_ID, $nfeId);
	}
	
	/**
	 * Get order_id
	 * @return string|null
	 */
	public function getOrderId() {
		return $this->_get(self::ORDER_ID);
	}
	
	/**
	 * Set order_id
	 * @param string $orderId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setOrderId($orderId) {
		return $this->setData(self::ORDER_ID, $orderId);
	}
	
	/**
	 * Retrieve existing extension attributes object or create a new one.
	 * @return \Eloom\BlingNfe\Api\Data\NfeExtensionInterface|null
	 */
	public function getExtensionAttributes() {
		return $this->_getExtensionAttributes();
	}
	
	/**
	 * Set an extension attributes object.
	 * @param \Eloom\BlingNfe\Api\Data\NfeExtensionInterface $extensionAttributes
	 * @return $this
	 */
	public function setExtensionAttributes(
		\Eloom\BlingNfe\Api\Data\NfeExtensionInterface $extensionAttributes
	) {
		return $this->_setExtensionAttributes($extensionAttributes);
	}
	
	/**
	 * Get bling_number
	 * @return string|null
	 */
	public function getBlingNumber() {
		return $this->_get(self::BLING_NUMBER);
	}
	
	/**
	 * Set bling_number
	 * @param string $blingNumber
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setBlingNumber($blingNumber) {
		return $this->setData(self::BLING_NUMBER, $blingNumber);
	}
	
	/**
	 * Get tracking_number
	 * @return string|null
	 */
	public function getTrackingNumber() {
		return $this->_get(self::TRACKING_NUMBER);
	}
	
	/**
	 * Set tracking_number
	 * @param string $trackingNumber
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setTrackingNumber($trackingNumber) {
		return $this->setData(self::TRACKING_NUMBER, $trackingNumber);
	}
	
	/**
	 * Get bling_id
	 * @return string|null
	 */
	public function getBlingId() {
		return $this->_get(self::BLING_ID);
	}
	
	/**
	 * Set bling_id
	 * @param string $blingId
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setBlingId($blingId) {
		return $this->setData(self::BLING_ID, $blingId);
	}
	
	/**
	 * Get access_key
	 * @return string|null
	 */
	public function getAccessKey() {
		return $this->_get(self::ACCESS_KEY);
	}
	
	/**
	 * Set access_key
	 * @param string $accessKey
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setAccessKey($accessKey) {
		return $this->setData(self::ACCESS_KEY, $accessKey);
	}
	
	/**
	 * Get status
	 * @return string|null
	 */
	public function getStatus() {
		return $this->_get(self::STATUS);
	}
	
	/**
	 * Set status
	 * @param string $status
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setStatus($status) {
		return $this->setData(self::STATUS, $status);
	}
	
	/**
	 * Get created_at
	 * @return string|null
	 */
	public function getCreatedAt() {
		return $this->_get(self::CREATED_AT);
	}
	
	/**
	 * Set created_at
	 * @param string $createdAt
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setCreatedAt($createdAt) {
		return $this->setData(self::CREATED_AT, $createdAt);
	}
	
	/**
	 * Get danfe_url
	 * @return string|null
	 */
	public function getDanfeUrl() {
		return $this->_get(self::DANFE_URL);
	}
	
	/**
	 * Set danfe_url
	 * @param string $danfeUrl
	 * @return \Eloom\BlingNfe\Api\Data\NfeInterface
	 */
	public function setDanfeUrl($danfeUrl) {
		return $this->setData(self::DANFE_URL, $danfeUrl);
	}
}

