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

namespace Eloom\BlingNfe\Block\Adminhtml\Form\Field;

use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\ShippingMethodColumn;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class ShipToBling extends AbstractFieldArray {
	
	private $methodsRenderer;
	
	/**
	 * Prepare rendering the new field by adding all the needed columns
	 */
	protected function _prepareToRender() {
		$this->addColumn('method', [
			'label' => __('Shipping method'),
			'class' => 'required-entry',
			'renderer' => $this->getMethodsRenderer()
		]);
		
		$this->addColumn('carrier', ['label' => __('Carrier'), 'class' => 'required-entry']);
		
		$this->addColumn('service', ['label' => __('Service'), 'class' => 'required-entry']);
		
		$this->_addAfter = false;
		$this->_addButtonLabel = __('Add');
	}
	
	/**
	 * Prepare existing row data object
	 *
	 * @param DataObject $row
	 * @throws LocalizedException
	 */
	protected function _prepareArrayRow(DataObject $row) {
		$options = [];
		
		$method = $row->getMethod();
		if ($method !== null) {
			$options['option_' . $this->getMethodsRenderer()->calcOptionHash($method)] = 'selected="selected"';
		}
		
		$row->setData('option_extra_attrs', $options);
	}
	
	/**
	 * @return OrderStatusColumn
	 * @throws LocalizedException
	 */
	private function getMethodsRenderer() {
		if (!$this->methodsRenderer) {
			$this->methodsRenderer = $this->getLayout()->createBlock(
				ShippingMethodColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->methodsRenderer;
	}
	
}