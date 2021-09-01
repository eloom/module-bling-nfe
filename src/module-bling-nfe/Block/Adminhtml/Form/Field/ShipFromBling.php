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

use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\AllCarriersColumn;
use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\ShippingMethodColumn;
use Eloom\BlingNfe\Block\Adminhtml\Form\Field\Column\TrackColumn;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class ShipFromBling extends AbstractFieldArray {
	
	private $allCarriersRenderer;
	
	private $methodsRenderer;
	
	private $trackRenderer;
	
	/**
	 * Prepare rendering the new field by adding all the needed columns
	 */
	protected function _prepareToRender() {
		$this->addColumn('method', [
			'label' => __('Order shipping method'),
			'class' => 'required-entry',
			'renderer' => $this->getMethodsRenderer()
		]);
		
		$this->addColumn('code', [
			'label' => __('Tracking shipping method'),
			'class' => 'required-entry',
			'renderer' => $this->getAllCarriersRenderer()
		]);
		
		$this->addColumn('tracker', [
			'label' => __('Tracker field'),
			'class' => 'required-entry',
			'renderer' => $this->getTrackerRenderer()
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
		
		$method = $row->getMethod();
		if ($method !== null) {
			$options['option_' . $this->getMethodsRenderer()->calcOptionHash($method)] = 'selected="selected"';
		}
		
		$code = $row->getCode();
		if ($code !== null) {
			$options['option_' . $this->getAllCarriersRenderer()->calcOptionHash($code)] = 'selected="selected"';
		}
		
		$tracker = $row->getTracker();
		if ($tracker !== null) {
			$options['option_' . $this->getTrackerRenderer()->calcOptionHash($tracker)] = 'selected="selected"';
		}
		
		$row->setData('option_extra_attrs', $options);
	}
	
	/**
	 * @return TrackColumn
	 * @throws LocalizedException
	 */
	private function getTrackerRenderer() {
		if (!$this->trackRenderer) {
			$this->trackRenderer = $this->getLayout()->createBlock(
				TrackColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->trackRenderer;
	}
	
	/**
	 * @return AllCarriersColumn
	 * @throws LocalizedException
	 */
	private function getAllCarriersRenderer() {
		if (!$this->allCarriersRenderer) {
			$this->allCarriersRenderer = $this->getLayout()->createBlock(
				AllCarriersColumn::class,
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		
		return $this->allCarriersRenderer;
	}
	
	/**
	 * @return ShippingMethodColumn
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