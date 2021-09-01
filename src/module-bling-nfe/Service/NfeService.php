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

namespace Eloom\BlingNfe\Service;

use Eloom\Bling\Helper\Data as BlingHelper;
use Eloom\BlingNfe\Lib\BlingApi;
use Eloom\BlingNfe\Lib\Domain\Request\Order;
use Magento\Framework\App\ObjectManager;

class NfeService {
	
	/**
	 * @var \DOMDocument
	 */
	private $dom;
	
	private $xml;
	
	private $order;
	
	public function __construct() {
		$this->dom = new \DOMDocument('1.0', 'UTF-8');
		$this->dom->xmlStandalone = true;
	}
	
	public function toXml(Order $order) {
		$this->order = $order;
		
		$nfeNode = $this->dom->appendChild($this->dom->createElement('pedido'));
		$nfeNode->appendChild($this->dom->createElement('numero', $this->order->getId()));
		$nfeNode->appendChild($this->dom->createElement('numero_loja', $this->order->getId()));
		$nfeNode->appendChild($this->dom->createElement('tipo', $this->order->getType()->key()));
		$nfeNode->appendChild($this->dom->createElement('nat_operacao', $this->order->getNatureOperation()));
		
		$nfeNode->appendChild($this->dom->createElement('vlr_frete', $this->order->getShippingAmount()));
		$nfeNode->appendChild($this->dom->createElement('vlr_desconto', $this->order->getDiscountAmount()));
		$nfeNode->appendChild($this->dom->createElement('vlr_despesas', $this->order->getInterestAmount()));
		
		/**
		 * Client
		 */
		$client = $this->order->client();
		$clientNode = $nfeNode->appendChild($this->dom->createElement('cliente'));
		$clientNode->appendChild($this->dom->createElement('nome', $client->getName()));
		$clientNode->appendChild($this->dom->createElement('tipoPessoa', $client->getPersonType()->key()));
		$clientNode->appendChild($this->dom->createElement('cpf_cnpj', $client->getTaxvat()));
		
		$address = $client->address();
		$clientNode->appendChild($this->dom->createElement('endereco', $address->getPlace()));
		$clientNode->appendChild($this->dom->createElement('numero', $address->getNumber()));
		$clientNode->appendChild($this->dom->createElement('complemento', $address->getComplement()));
		$clientNode->appendChild($this->dom->createElement('bairro', $address->getDistrict()));
		$clientNode->appendChild($this->dom->createElement('cidade', $address->getCity()));
		$clientNode->appendChild($this->dom->createElement('uf', $address->getState()));
		$clientNode->appendChild($this->dom->createElement('cep', $address->getPostCode()));
		$clientNode->appendChild($this->dom->createElement('fone', $address->getPhone()));
		$clientNode->appendChild($this->dom->createElement('email', $address->getEmail()));
		$nfeNode->appendChild($clientNode);
		
		/**
		 * Transport
		 */
		if ($this->order->hasTransport()) {
			$transport = $this->order->transport();
			$transportNode = $nfeNode->appendChild($this->dom->createElement('transporte'));
			$transportNode->appendChild($this->dom->createElement('transportadora', $transport->getName()));
			$transportNode->appendChild($this->dom->createElement('tipo_frete', 'R'));
			
			/**
			 * Tag
			 */
			$tag = $transport->tag();
			$tagNode = $transportNode->appendChild($this->dom->createElement('dados_etiqueta'));
			$tagNode->appendChild($this->dom->createElement('nome', $tag->getName()));
			$tagNode->appendChild($this->dom->createElement('endereco', $tag->getPlace()));
			$tagNode->appendChild($this->dom->createElement('numero', $tag->getNumber()));
			$tagNode->appendChild($this->dom->createElement('complemento', $tag->getComplement()));
			$tagNode->appendChild($this->dom->createElement('bairro', $tag->getDistrict()));
			$tagNode->appendChild($this->dom->createElement('municipio', $tag->getCity()));
			$tagNode->appendChild($this->dom->createElement('uf', $tag->getState()));
			$tagNode->appendChild($this->dom->createElement('cep', $tag->getPostCode()));
			$transportNode->appendChild($tagNode);
		}
		
		/**
		 * Items
		 */
		$items = $this->order->items();
		$itemsNode = $nfeNode->appendChild($this->dom->createElement('itens'));
		foreach ($items as $item) {
			$itemNode = $itemsNode->appendChild($this->dom->createElement('item'));
			$itemNode->appendChild($this->dom->createElement('codigo', $item->getCode()));
			$itemNode->appendChild($this->dom->createElement('descricao', $item->getDescription()));
			$itemNode->appendChild($this->dom->createElement('un', $item->getUm()->key()));
			if (null != $item->getAdditionalInformation()) {
				$itemNode->appendChild($this->dom->createElement('informacoes_adicionais', $item->getAdditionalInformation()));
			}
			
			/**
			 * CEST
			 */
			$itemNode->appendChild($this->dom->createElement('class_fiscal', $item->getNcm()));
			$itemNode->appendChild($this->dom->createElement('origem', $item->getSource()));
			if (null != $item->getCest()) {
				$itemNode->appendChild($this->dom->createElement('cest', $item->getCest()));
			}
			if (null != $item->getGtin()) {
				$itemNode->appendChild($this->dom->createElement('gtin', $item->getGtin()));
			}
			$itemNode->appendChild($this->dom->createElement('qtde', $item->getQty()));
			$itemNode->appendChild($this->dom->createElement('vlr_unit', strval($item->getPrice())));
			$itemNode->appendChild($this->dom->createElement('tipo', $item->getType()->key()));
			$itemsNode->appendChild($itemNode);
		}
		$nfeNode->appendChild($itemsNode);
		
		/**
		 * Installments
		 */
		$installments = $this->order->installments();
		if (count($installments)) {
			$installmentsNode = $nfeNode->appendChild($this->dom->createElement('parcelas'));
			
			foreach ($installments as $installment) {
				$installmentNode = $installmentsNode->appendChild($this->dom->createElement('parcela'));
				if ($installment->getDays()) {
					$installmentNode->appendChild($this->dom->createElement('dias', strval($installment->getDays())));
				}
				if ($installment->getDate()) {
					$installmentNode->appendChild($this->dom->createElement('data', $installment->getDate()));
				}
				if ($installment->getTotal()) {
					$installmentNode->appendChild($this->dom->createElement('vlr', strval($installment->getTotal())));
				}
				if ($installment->getMethod()) {
					$installmentNode->appendChild($this->dom->createElement('forma', $installment->getMethod()));
				}
				if ($installment->getObservations()) {
					$installmentNode->appendChild($this->dom->createElement('obs', $installment->getObservations()));
				}
				
				$installmentsNode->appendChild($installmentNode);
			}
			
			$nfeNode->appendChild($installmentsNode);
		}
		
		$this->dom->appendChild($nfeNode);
		$this->xml = trim($this->dom->saveXML());
		
		return $this;
	}
	
	public function create():string {
		$storeId = $this->order->getStoreId();
		$helper = ObjectManager::getInstance()->get(BlingHelper::class);
		$apiKey = $helper->getApiKey($storeId);
		
		$data = ['xml' => rawurlencode($this->xml)];
		$blingApi = new BlingApi($apiKey);
		return $blingApi->nfes()->create($data);
	}
}