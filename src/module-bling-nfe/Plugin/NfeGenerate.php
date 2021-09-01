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

namespace Eloom\BlingNfe\Plugin;

use Eloom\BlingNfe\Helper\Data as Helper;
use Magento\Backend\Model\UrlInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderRepository;

class NfeGenerate {
	
	protected $urlBuilder;
	
	/**
	 * @var Helper
	 */
	private $helper;
	
	private $orderRepository;
	
	public function __construct(UrlInterface             $urlBuilder,
	                            Helper                   $helper,
	                            OrderRepositoryInterface $orderRepository) {
		$this->urlBuilder = $urlBuilder;
		$this->helper = $helper;
		$this->orderRepository = $orderRepository;
	}
	
	public function beforeSetLayout(\Magento\Sales\Block\Adminhtml\Order\View $subject) {
		$order = $this->orderRepository->get($subject->getOrderId());
		if (!$this->helper->isActiveAR($order->getStoreId())) {
			return;
		}
		
		$url = $this->urlBuilder->getUrl('eloom_blingnfe/nfe/index', ['order_id' => $subject->getOrderId()]);
		$message = __('Are you sure you want to generate NF-e?');
		
		$subject->addButton(
			'nfe_generate',
			[
				'label' => __('NF-e A/R Generate'),
				'onclick' => "confirmSetLocation('{$message}', '{$url}')",
				'class' => 'nfe'
			],
			-1
		);
	}
}