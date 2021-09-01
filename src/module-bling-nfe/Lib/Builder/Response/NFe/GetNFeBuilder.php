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

namespace Eloom\BlingNfe\Lib\Builder\Response\NFe;

use Eloom\BlingNfe\Helper\Data as Helper;
use Eloom\BlingNfe\Lib\Domain\Response\Order;

class GetNFeBuilder {
	
	private $helper;
	
	public function __construct(Helper $helper) {
		$this->helper = $helper;
	}
	
	public function build(string $data, $shippingDto = null): Order {
		$json = json_decode($data, false);
		$order = new Order();
		
		if (isset($json->retorno->erros)) {
			if (is_array($json->retorno->erros)) {
				foreach ($json->retorno->erros as $error) {
					$order->addError($error->erro->cod, $error->erro->msg);
				}
			}
			if ($json->retorno->erros instanceof stdClass) {
				$error = $json->retorno->erros;
				$order->addError($error->erro->cod, $error->erro->msg);
			}
		}
		
		if (isset($json->retorno->notasfiscais)) {
			foreach ($json->retorno->notasfiscais as $nf) {
				$order->addNfe()
					->setId($nf->notafiscal->id)
					->setNumber($nf->notafiscal->numero)
					->setUrl($nf->notafiscal->linkDanfe)
					->setStatus($nf->notafiscal->situacao);
				
				if ($nf->notafiscal->chaveAcesso) {
					$order->getLastNFe()->setAccessKey($nf->notafiscal->chaveAcesso);
				}
				
				if (isset($nf->notafiscal->codigos_rastreamento)) {
					if (null != $nf->notafiscal->codigos_rastreamento->codigo_rastreamento) {
						$order->getLastNFe()->addTracker($nf->notafiscal->codigos_rastreamento->codigo_rastreamento);
					}
				}
				if (!$order->getLastNFe()->hasTracker()) {
					if (null != $shippingDto && $shippingDto->isTrackerFromNfeNumber()) {
						$order->getLastNFe()->addTracker($nf->notafiscal->numero);
					}
				}
			}
		}
		
		return $order;
	}
}