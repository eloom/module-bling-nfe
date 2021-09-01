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

use Eloom\Bling\Lib\Http\BlingClient;
use Eloom\BlingNfe\Lib\Repositories\NFeRepository;

class BlingApi {
	
	/**
	 * @var BlingClient
	 */
	protected $client;
	
	public function __construct(string $apiKey) {
		$this->client = new BlingClient($apiKey);
	}
	
	public function getClient(): BlingClient {
		return $this->client;
	}
	
	public function nfes(): NFeRepository {
		return new NFeRepository($this->client);
	}
}