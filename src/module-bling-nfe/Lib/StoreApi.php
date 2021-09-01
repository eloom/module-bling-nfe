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

namespace Eloom\BlingNfe\Lib;

use Eloom\Bling\Lib\Http\StoreClient;
use Eloom\BlingNfe\Lib\Repositories\OrderRestRepository;

class StoreApi {
	
	/**
	 * @var StoreClient
	 */
	protected $client;
	
	public function __construct(string $storeId) {
		$this->client = new StoreClient($storeId);
	}
	
	public function getClient(): StoreClient {
		return $this->client;
	}
	
	public function orders(): OrderRestRepository {
		return new OrderRestRepository($this->client);
	}
}