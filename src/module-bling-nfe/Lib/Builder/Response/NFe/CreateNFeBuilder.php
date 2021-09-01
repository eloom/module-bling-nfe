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

use Eloom\BlingNfe\Lib\Domain\Response\Order;

class CreateNFeBuilder {
	
	public function build(string $data): Order {
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
					->setId($nf->notaFiscal->idNotaFiscal)
					->setNumber($nf->notaFiscal->numero);
				
				if (isset($nf->notaFiscal->codigos_rastreamento)) {
					if (null != $nf->notaFiscal->codigos_rastreamento->codigo_rastreamento) {
						$order->getLastNFe()->addTracker($nf->notaFiscal->codigos_rastreamento->codigo_rastreamento);
					}
				}
			}
		}
		
		return $order;
	}
}