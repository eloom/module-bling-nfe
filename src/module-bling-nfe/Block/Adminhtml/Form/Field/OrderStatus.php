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

use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\OrderStatusColumn;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class OrderStatus extends AbstractFieldArray {
	
	private $initialStatusRenderer;
	
	private $finalStatusRenderer;
	
	/**
	 * Prepare rendering the new field by adding all the needed columns
	 */
	protected function _prepareToRender() {
		$this->addColumn('initial', [
			'label' => __('Initial status'),
			'class' => 'required-entry',
			'renderer' => $this->getInitialOrderStatusRenderer()
		]);
		
		$this->addColumn('operation', ['label' => __('Nature of operation'), 'class' => 'required-entry']);
		
		$this->addColumn('final', [
			'label' => __('Final status'),
			'class' => 'required-entry',
			'renderer' => $this->getFinalOrderStatusRenderer()
		]);
		
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
		
		$initial = $row->getInitial();
		if ($initial !== null) {
			$options['option_' . $this->getInitialOrderStatusRenderer()->calcOptionHash($initial)] = 'selected="selected"';
		}
		
		$final = $row->getFinal();
		if ($final !== null) {
			$options['option_' . $this->getFinalOrderStatusRenderer()->calcOptionHash($final)] = 'selected="selected"';
		}
		
		$row->setData('option_extra_attrs', $options);
	}
	
	/**
	 * @return OrderStatusColumn
	 * @throws LocalizedException
	 */
	private function getInitialOrderStatusRenderer() {
		if (!$this->initialStatusRenderer) {
			$this->initialStatusRenderer = $this->getLayout()->createBlock(
				OrderStatusColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->initialStatusRenderer;
	}
	
	/**
	 * @return OrderStatusColumn
	 * @throws LocalizedException
	 */
	private function getFinalOrderStatusRenderer() {
		if (!$this->finalStatusRenderer) {
			$this->finalStatusRenderer = $this->getLayout()->createBlock(
				OrderStatusColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->finalStatusRenderer;
	}
	
}