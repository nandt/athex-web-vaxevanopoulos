<?php

namespace Drupal\athex_d_mde\Controller;

use Drupal\athex_d_mde\Service\TickerTapeService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;


class TickerTapeController extends ControllerBase {

	protected $renderer;
	protected $service;

	public function __construct(
		Renderer $renderer,
		TickerTapeService $service
	) {
		$this->renderer = $renderer;
		$this->service = $service;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('renderer'),
			$container->get('athex_d_mde.ticker_tape')
		);
	}

	public function getData() {
		$result = $this->service->getTapeItemData();

		foreach ($result as $idx => $item) {
			foreach ($item as $key => $value) {
				if (is_array($value)) {
					$result[$idx][$key] = $this->renderer->renderPlain($value);
				}
			}
		}

		$result = [
			'infoHtml' => $this->service->getPrimaryInfoHtml(),
			'items' => $result
		];
		$response = new CacheableJsonResponse($result);
		$response->getCacheableMetadata()->setCacheMaxAge(9);
		return $response;
	}
}
