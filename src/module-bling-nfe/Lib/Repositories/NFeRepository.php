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

use Eloom\Bling\Lib\Http\BlingClient;

class NFeRepository {
	
	/**
	 * @var Client
	 */
	protected $client;
	
	public function __construct(BlingClient $client) {
		$this->client = $client;
	}
	
	public function all(array $filters = []): ?string {
		$options = [];
		
		foreach ($filters as $k => $v) {
			$filters[$k] = $k . '[' . $v . ']';
		}
		
		if (count($filters)) {
			$options['filters'] = implode('; ', $filters);
		}
		
		return $this->client->get('notasfiscais/json/', $options);
	}
	
	public function find(string $numero, string $serie): ?string {
		return $this->client->get("notafiscal/$numero/$serie/json/");
	}
	
	public function create(array $data): ?string {
		return $this->client->post('notafiscal/json/', $data);
	}
	
	public function send(int $numero, int $serie, $sendEmail = false): ?string {
		$options = [];
		
		$options['number'] = $numero;
		$options['serie'] = $serie;
		
		if ($sendEmail) {
			$options['sendEmail'] = 'true';
		}
		
		return $this->client->post("notafiscal/$numero/$serie/json/", $options);
	}
}