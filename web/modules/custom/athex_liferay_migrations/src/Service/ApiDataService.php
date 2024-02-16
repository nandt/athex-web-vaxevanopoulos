<?php

namespace Drupal\athex_liferay_migrations\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\AthexData\LiferayArticle;

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

	public function call(ApiEndpoints $endpoint, array $args = []) {
		$args += [
			'companyId' => $this->config->get('companyId')
		];
		$multipart = [];

		foreach ($args as $k => $v) {
			$multipart[] = [
				'name' => $k,
				'contents' => $v
			];
		}

		$rq = null;
		// try {
		$rq = $this->http->request(
			"GET",
			"{$this->getBaseUrl()}/{$endpoint->value}",
			[
				'auth' => [
					$this->config->get('username'),
					$this->config->get('password')
				],
				'multipart' => $multipart,
				'verify' => false //TODO: turn into config flag
			]
		);
		// }
		// catch (RequestException $e) {
		// 	if ($e->hasResponse()) {
		// 		$rsp = $e->getResponse();
		// 		$this->logger->error(
		// 			"HTTP "
		// 			. $rsp->getStatusCode()
		// 			. " with message:\n\t"
		// 			. $rsp->getBody()->getContents()
		// 		);
		// 	}
		// 	else
		// 		$this->logger->error('RequestException with no response');
		// }
		// catch (ConnectException $e) {
		// 	$this->logger->error('Connection error');
		// }

		$rs = json_decode($rq->getBody()->getContents(), true);

		if (isset($rs['exception']))
			throw new \Error("Liferay API Exception: \"{$rs['exception']}\"");

		return $rs;
	}

	public function getLiferayArticle($resourcePrimKey) {
		$data = $this->call(
			ApiEndpoints::JOURNALARTICLE__GET_LATEST,
			[
				'resourcePrimKey' => $resourcePrimKey
			]
		);
		return new LiferayArticle($data);
	}
}
