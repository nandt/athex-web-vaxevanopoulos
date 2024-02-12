<?php

namespace Drupal\athex_d_mde\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_mde\Service\IndicesOverviewTablesService;
use Symfony\Component\HttpFoundation\Response;

class FragmentsController extends ControllerBase {

	protected Renderer $renderer;
	protected IndicesOverviewTablesService $service;

	public function __construct(
		Renderer $renderer,
		IndicesOverviewTablesService $service
	) {
		$this->renderer = $renderer;
		$this->service = $service;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('renderer'),
			$container->get('athex_d_mde.indices_overview_tables')
		);
	}

	private function renderFragment($result) {
		$result = $this->renderer->renderRoot($result);
		// $result = $this->renderer->renderPlain($result);
		// $result = new CacheableResponse($result);
		// $result->getCacheableMetadata()->setCacheMaxAge(9);
		return new Response($result);
	}

	public function indexTabs() {
		$params = \Drupal::request()->query;
		return $this->renderFragment(
			$this->service->renderIndex(
				$params->get('symbol')
			)
		);
	}

	public function indexTable() {
		$params = \Drupal::request()->query;
		return $this->renderFragment(
			$this->service->renderTable(
				$params->get('symbol'),
				$params->get('table')
			)
		);
	}

	public function indexSummary() {
		// $result = [
		// 	// ...
		// ];
		// $result = $this->renderer->renderPlain($result);
		// $result = new CacheableResponse($result);
		// $result->getCacheableMetadata()->setCacheMaxAge(9);
		// return $result;
	}
}
