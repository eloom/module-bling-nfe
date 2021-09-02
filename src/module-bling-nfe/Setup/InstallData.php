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

namespace Eloom\BlingNfe\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
	
	private $eavSetupFactory;
	
	public function __construct(EavSetupFactory $eavSetupFactory) {
		$this->eavSetupFactory = $eavSetupFactory;
	}
	
	public function install(ModuleDataSetupInterface $setup,
	                        ModuleContextInterface   $context) {
		
		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_ncm')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_ncm', [
				'group' => 'NF-e',
				'type' => 'varchar',
				'label' => 'NCM',
				'input' => 'text',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => true,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 1
			]);
		}
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_um')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_um', [
				'group' => 'NF-e',
				'type' => 'varchar',
				'label' => 'Unidade de Medida',
				'input' => 'select',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => true,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 2,
				'option' => array(
					'values' => array(
						0 => 'Unidade | UN',
						1 => 'Caixa | CX',
						2 => 'Pacote | PC'
					),
				)
			]);
		}
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_item_type')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_item_type', [
				'group' => 'NF-e',
				'type' => 'varchar',
				'label' => 'Tipo do Item',
				'input' => 'select',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => true,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 3,
				'option' => array(
					'values' => array(
						0 => 'Produto | P',
						1 => 'Serviço | S'
					),
				),
				'default' => 0
			]);
		}
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_product_source')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_product_source', [
				'group' => 'NF-e',
				'type' => 'int',
				'label' => 'Origem',
				'input' => 'select',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => true,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 4,
				'option' => array(
					'values' =>
						array(
							0 => '0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8',
							1 => '1 - Estrangeira - Importação direta, exceto a indicada no código 6',
							2 => '2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7',
							3 => '3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%',
							4 => '4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes',
							5 => '5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%',
							6 => '6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX',
							7 => '7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante em lista da CAMEX',
							8 => '8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%'
						)
				)
			]);
		}
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_cest')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_cest', [
				'group' => 'NF-e',
				'type' => 'varchar',
				'label' => 'CEST',
				'input' => 'text',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 5
			]);
		}
		
		if (!$eavSetup->getAttributeId(Product::ENTITY, 'nfe_gtin')) {
			$eavSetup->addAttribute(Product::ENTITY, 'nfe_gtin', [
				'group' => 'NF-e',
				'type' => 'varchar',
				'label' => 'Gtin',
				'input' => 'text',
				'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => 'simple,bundle,grouped,configurable',
				'sort_order' => 6
			]);
		}
	}
}