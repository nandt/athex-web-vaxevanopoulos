<?php

namespace Drupal\athex_inbroker_integration\Controller;

use Drupal\athex_inbroker_integration\Service\TickerTapeService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;

class TickerTapeController extends ControllerBase {

	protected $service;

	public function __construct(
		TickerTapeService $service
	) {
		$this->service = $service;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_inbroker.ticker_tape')
		);
	}

	public function getData() {
		$result = [
			'infoHtml' => $this->service->getPrimaryInfoHtml(),
			'items' => $this->service->getTapeItemData()
		];
		$response = new CacheableJsonResponse($result);
		$response->getCacheableMetadata()->setCacheMaxAge(9);
		return $response;
	}
}
