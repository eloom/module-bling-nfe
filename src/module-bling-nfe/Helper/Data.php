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

namespace Eloom\BlingNfe\Helper;

use Eloom\BlingNfe\Lib\Dto\OrderStatus;
use Eloom\BlingNfe\Lib\Dto\ShipFromBling;
use Eloom\BlingNfe\Lib\Dto\ShipToBling;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Unserialize\Unserialize;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper {
	
	const XML_PATH_AR_ACTIVE = 'eloom_bling/nfe_ar/active';
	
	const XML_PATH_AR_SERIE = 'eloom_bling/nfe_ar/serie';
	
	const XML_PATH_AR_COMMENT = 'eloom_bling/nfe_ar/comment';
	
	const XML_PATH_AR_ORDER_STATUS = 'eloom_bling/nfe_ar/order_status';
	
	const XML_PATH_SHIP_TO_BLING = 'eloom_bling/nfe/ship_to_bling';
	
	const XML_PATH_STATUS_TO_SHIP = 'eloom_bling/nfe/status_to_ship';
	
	const XML_PATH_SHIP_FROM_BLING = 'eloom_bling/nfe/ship_from_bling';
	
	protected $storeManager;
	
	public function __construct(Context               $context,
	                            StoreManagerInterface $storeManager) {
		$this->storeManager = $storeManager;
		parent::__construct($context);
	}
	
	public function isActiveAR($storeId = null) {
		return (bool)$this->scopeConfig->getValue(self::XML_PATH_AR_ACTIVE, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	public function getCommentAR($storeId = null) {
		return $this->scopeConfig->getValue(self::XML_PATH_AR_COMMENT, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	public function getSerieAR($storeId = null) {
		return $this->scopeConfig->getValue(self::XML_PATH_AR_SERIE, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	public function getOrderStatusToShip($storeId = null) {
		return $this->scopeConfig->getValue(self::XML_PATH_STATUS_TO_SHIP, ScopeInterface::SCOPE_STORE, $storeId);
	}
	
	private function getSerializedOrderStatusAR($storeId = null) {
		$value = $this->scopeConfig->getValue(self::XML_PATH_AR_ORDER_STATUS, ScopeInterface::SCOPE_STORE, $storeId);
		if ($this->isSerialized($value)) {
			$unserializer = ObjectManager::getInstance()->get(Unserialize::class);
		} else {
			$unserializer = ObjectManager::getInstance()->get(Json::class);
		}
		
		return $unserializer->unserialize($value);
	}
	
	public function isAllowedToGenerateNfeAR($status, $storeId = null) {
		$isEnabled = false;
		
		$maps = $this->getSerializedOrderStatusAR($storeId);
		foreach ($maps as $key => $map) {
			if ($status == $map['initial']) {
				$isEnabled = true;
				break;
			}
		}
		
		return $isEnabled;
	}
	
	public function getOrderStatusNfeAR($status, $storeId = null): ?OrderStatus {
		$dto = null;
		
		$maps = $this->getSerializedOrderStatusAR($storeId);
		foreach ($maps as $key => $map) {
			if ($status == $map['initial']) {
				$dto = new OrderStatus($map['initial'], $map['operation'], $map['final']);
				break;
			}
		}
		
		return $dto;
	}
	
	private function getSerializedShipToBling($storeId = null) {
		$value = $this->scopeConfig->getValue(self::XML_PATH_SHIP_TO_BLING, ScopeInterface::SCOPE_STORE, $storeId);
		if ($this->isSerialized($value)) {
			$unserializer = ObjectManager::getInstance()->get(Unserialize::class);
		} else {
			$unserializer = ObjectManager::getInstance()->get(Json::class);
		}
		
		return $unserializer->unserialize($value);
	}
	
	public function getShipToBling($method, $storeId = null): ?ShipToBling {
		$dto = null;
		
		$maps = $this->getSerializedShipToBling($storeId);
		foreach ($maps as $key => $map) {
			if ($method == $map['method']) {
				$dto = new ShipToBling($map['method'], $map['carrier'], $map['service']);
				break;
			}
		}
		
		return $dto;
	}
	
	private function getSerializedShipFromBling($storeId = null) {
		$value = $this->scopeConfig->getValue(self::XML_PATH_SHIP_FROM_BLING, ScopeInterface::SCOPE_STORE, $storeId);
		if ($this->isSerialized($value)) {
			$unserializer = ObjectManager::getInstance()->get(Unserialize::class);
		} else {
			$unserializer = ObjectManager::getInstance()->get(Json::class);
		}
		
		return $unserializer->unserialize($value);
	}
	
	public function getShipFromBling($method, $storeId = null): ?ShipFromBling {
		$dto = null;
		
		$maps = $this->getSerializedShipFromBling($storeId);
		foreach ($maps as $key => $map) {
			if ($method == $map['method']) {
				$dto = new ShipFromBling($map['method'], $map['code'], $map['tracker']);
				break;
			}
		}
		
		return $dto;
	}
	
	/**
	 * Check if value is a serialized string
	 *
	 * @param string $value
	 * @return boolean
	 */
	private function isSerialized($value) {
		return (boolean)preg_match('/^((s|i|d|b|a|O|C):|N;)/', $value);
	}
}