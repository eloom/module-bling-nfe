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

use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\PaymentMethodColumn;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class Payment extends AbstractFieldArray {
	
	private $methodsRenderer;
	
	/**
	 * Prepare rendering the new field by adding all the needed columns
	 */
	protected function _prepareToRender() {
		$this->addColumn('method', [
			'label' => __('Payment method'),
			'class' => 'required-entry',
			'renderer' => $this->getMethodsRenderer()
		]);
		
		$this->addColumn('description', ['label' => __('Description'), 'class' => 'required-entry']);
		
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
	 * @return PaymentMethodColumn
	 * @throws LocalizedException
	 */
	private function getMethodsRenderer() {
		if (!$this->methodsRenderer) {
			$this->methodsRenderer = $this->getLayout()->createBlock(
				PaymentMethodColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->methodsRenderer;
	}
}