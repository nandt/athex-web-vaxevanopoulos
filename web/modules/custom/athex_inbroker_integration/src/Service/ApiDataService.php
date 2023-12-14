<?php

namespace Drupal\athex_inbroker_integration\Service;

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

	private function call($host_type, $transaction, $args) {
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
	}

	// only delayed will probably be used
	// public function callRealtime($transaction, $args = []) {
	// 	return $this->call('realtime', $transaction, $args);
	// }

	public function callDelayed($transaction, $args = []) {
		return $this->call('delayed', $transaction, $args);
	}
}
