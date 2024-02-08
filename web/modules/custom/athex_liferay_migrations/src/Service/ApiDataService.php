<?php

namespace Drupal\athex_liferay_migrations\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

use Drupal\athex_liferay_migrations\ApiEndpoints;


class ApiDataService {

	protected $logger;
	protected $config;
	protected $http;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface $configFactory,
		ClientInterface $http
	) {
		$this->logger = $loggerFactory->get('athex_liferay_migrations');
		$this->config = $configFactory->get('athex_liferay_migrations.settings');
		$this->http = $http;
  	}

	private function getBaseUrl() {
		$baseUrl = $this->config->get('base_url');
		$baseUrl = preg_replace('/\/$/i', '', $baseUrl);
		return $baseUrl;
	}

	public function call(ApiEndpoints $endpoint, array $args) {
		$args += [
			'companyId' => $this->config->get('companyId')
		];

		$rq = $this->http->request(
			"GET",
			"{$this->getBaseUrl()}/{$endpoint->value}",
			[
				'auth' => [
					$this->config->get('username'),
					$this->config->get('password')
				],
				'form_params' => $args
			]
		);

		return json_decode($rq->getBody()->getContents(), true);
	}
}
