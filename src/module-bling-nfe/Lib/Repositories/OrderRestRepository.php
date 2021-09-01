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

namespace Eloom\BlingNfe\Lib\Repositories;

use Eloom\Bling\Lib\Http\StoreClient;

class OrderRestRepository {
	
	/**
	 * @var StoreClient
	 */
	protected $client;
	
	public function __construct(StoreClient $client) {
		$this->client = $client;
	}
	
	public function comments($orderId, array $data): ?string {
		return $this->client->post("orders/{$orderId}/comments", $data);
	}
	
	public function invoice($orderId, array $data): ?string {
		return $this->client->post("order/{$orderId}/invoice", $data);
	}
	
	public function ship($orderId, array $data): ?string {
		return $this->client->post("order/{$orderId}/ship", $data);
	}
}