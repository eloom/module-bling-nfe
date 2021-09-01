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

namespace Eloom\BlingNfe\Block\Adminhtml\Order\View\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\View\Element\Text\ListText;

class Nfe extends ListText implements TabInterface {
	
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $registry = null;
	
	public function __construct(\Magento\Backend\Block\Template\Context $context,
	                            \Magento\Framework\Registry             $registry,
	                            array                                   $data = []) {
		$this->registry = $registry;
		parent::__construct($context, $data);
	}
	
	public function getOrder() {
		return $this->registry->registry('current_order');
	}
	
	public function getOrderId() {
		return $this->getOrder()->getEntityId();
	}
	
	public function getOrderIncrementId() {
		return $this->getOrder()->getIncrementId();
	}
	
	public function getTabLabel() {
		return __('Bling NF-e');
	}
	
	public function getTabTitle() {
		return __('Bling NF-e');
	}
	
	public function canShowTab() {
		return true;
	}
	
	public function isHidden() {
		return false;
	}
}