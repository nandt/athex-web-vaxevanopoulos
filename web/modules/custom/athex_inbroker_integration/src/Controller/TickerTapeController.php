<?php

namespace Drupal\athex_inbroker_integration\Controller;

use Drupal\athex_inbroker_integration\Service\ApiDataService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;

class TickerTapeController extends ControllerBase {

	protected $api;

	public function __construct(
		ApiDataService $api
	) {
		$this->api = $api;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_inbroker.api_data')
		);
	}

	public function getData() {
		$result = $this->api->callRealtime('Info', ['code' => 'ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH']);

		//TODO: Transform $result for ticker tape

		$response = new CacheableJsonResponse($result);
		$response->getCacheableMetadata()->setCacheMaxAge(9);
		return $response;
	}
}
