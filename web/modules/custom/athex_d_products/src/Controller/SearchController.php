<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_products\AthexSearch\StockSearch;


class SearchController extends ControllerBase {

	// protected $layout;
	// protected $overview_data;

	public function __construct(
		// StockPageLayoutService $layout,
		// StockOverviewDataService $overview_data
	) {
		// $this->layout = $layout;
		// $this->overview_data = $overview_data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			// $container->get('athex_d_products.layout'),
			// $container->get('athex_d_products.overview_data')
		);
	}

	public function getPage() {
		$search = new StockSearch();
		return $search->render();
	}
}
