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

		//dummy data
		$result = [
			'items' => [
				[
					'symbol' => 'ETE.ATH',
					'value' => 5.1 + (rand(-5, 5) * 0.1),
					'change' => 1
				], [
					'symbol' => 'ALPHA.ATH',
					'value' => 1.2 + (rand(-5, 5) * 0.1),
					'change' => 0.5
				], [
					'symbol' => 'TPEIR.ATH',
					'value' => 2.3 + (rand(-5, 5) * 0.1),
					'change' => -0.5
				], [
					'symbol' => 'EXAE.ATH',
					'value' => 3.4 + (rand(-5, 5) * 0.1),
					'change' => 0
				]
			]
		];

		$response = new CacheableJsonResponse($result);
		$response->getCacheableMetadata()->setCacheMaxAge(9);
		return $response;
	}
}
