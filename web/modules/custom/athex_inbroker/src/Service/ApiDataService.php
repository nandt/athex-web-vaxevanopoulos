<?php

namespace Drupal\athex_inbroker\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

class ApiDataService {

	protected $logger;
	protected $config;
	protected $http;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface $configFactory,
		ClientInterface $http
	) {
		$this->logger = $loggerFactory->get('athex_inbroker');
		$this->config = $configFactory->get('athex_inbroker.settings');
		$this->http = $http;
  	}

	/*private function call($host_type, $transaction, $args) {
		$dataHost = $this->config->get($host_type . '_host');
		$dataHost = preg_replace('/\/$/i', '', $dataHost);
		$args += [
			'userName' => $this->config->get('userName'),
			'company' => $this->config->get('company'),
			'IBSessionId' => $this->config->get('IBSessionId')
		];
		$args += [
			'lang' => 'GR',
			'format' => 'json'
		];
		$args = http_build_query($args);
		$get = $this->http->get(
			$dataHost . '/' . $transaction . '?' . $args
		);
		$data = json_decode($get->getBody()->getContents(), true);
		$data = $data['inbroker-transactions'] ?? [];
		$data = $data['row'];
		return $data;
	}*/
	private function call($host_type, $transaction, $args) {
		$dataHost = $this->config->get($host_type . '_host');
		$dataHost = preg_replace('/\/$/i', '', $dataHost);
		$args += [
			'userName' => $this->config->get('userName'),
			'company' => $this->config->get('company'),
			'IBSessionId' => $this->config->get('IBSessionId'),
			'lang' => 'GR',
			'format' => 'json',
		];

		$args = http_build_query($args);

		try {
			$response = $this->http->get($dataHost . '/' . $transaction . '?' . $args);
			$data = json_decode($response->getBody()->getContents(), true);

			// Check for 'inbroker-transactions' and 'row' keys
			if (isset($data['inbroker-transactions']['row'])) {
				return $data['inbroker-transactions']['row'];
			} else {
				// Log missing 'row' key
				$this->logger->error('The "row" key is missing in the API response for transaction: @transaction', ['@transaction' => $transaction]);
				return []; // Return an empty array or any default value as appropriate
			}
		} catch (\Exception $e) {
			// Log exception details
			$this->logger->error('API call to @transaction failed with error: @error', ['@transaction' => $transaction, '@error' => $e->getMessage()]);
			return []; // Return an empty array or any default value as appropriate
		}
	}


	// only delayed will probably be used
	// public function callRealtime($transaction, $args = []) {
	// 	return $this->call('realtime', $transaction, $args);
	// }

	public function callDelayed($transaction, $args = []) {
		$result = $this->call('delayed', $transaction, $args);
		if (!array_is_list($result)) $result = [$result];
		return $result;
	}
}
