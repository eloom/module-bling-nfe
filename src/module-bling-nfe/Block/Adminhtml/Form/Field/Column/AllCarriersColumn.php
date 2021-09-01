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

namespace Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Html\Select;

class AllCarriersColumn extends Select {
	
	/**
	 * Set "name" for <select> element
	 *
	 * @param string $value
	 * @return $this
	 */
	public function setInputName($value) {
		return $this->setName($value);
	}
	
	/**
	 * Set "id" for <select> element
	 *
	 * @param $value
	 * @return $this
	 */
	public function setInputId($value) {
		return $this->setId($value);
	}
	
	/**
	 * Render block HTML
	 *
	 * @return string
	 */
	public function _toHtml(): string {
		if (!$this->getOptions()) {
			$this->setOptions($this->getSourceOptions());
		}
		
		return parent::_toHtml();
	}
	
	private function getSourceOptions(): array {
		$scopeConfig = ObjectManager::getInstance()->get(\Magento\Framework\App\Config\ScopeConfigInterface::class);
		$shippingConfig = ObjectManager::getInstance()->get(\Magento\Shipping\Model\Config::class);
		
		$methods = [['value' => '', 'label' => '']];
		$carriers = $shippingConfig->getAllCarriers();
		foreach ($carriers as $carrierCode => $carrierModel) {
			if (!$carrierModel->isActive()) {
				continue;
			}
			$carrierMethods = $carrierModel->getAllowedMethods();
			if (!$carrierMethods) {
				continue;
			}
			$carrierTitle = $scopeConfig->getValue('carriers/' . $carrierCode . '/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
			$methods[] = ['value' => $carrierCode, 'label' => $carrierTitle];
		}
		
		return $methods;
	}
}