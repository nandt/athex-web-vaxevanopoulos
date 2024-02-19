<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\athex_d_products\AthexRendering\ProductSearch;

// Add this line

use Drupal\athex_d_mde\AthexRendering\DataTable;


class StockSearchController extends ControllerBase
{
	protected $pluginManager;
	protected $messenger;

	public function __construct($pluginManager, MessengerInterface $messenger)
	{
		$this->pluginManager = $pluginManager;
		$this->messenger = $messenger;
		$this->logger = \Drupal::logger('product_search_controller');
	}

	public static function create(ContainerInterface $container)
	{
		return new static(
			$container->get('plugin.manager.product_search'),
			$container->get('messenger')
		);
	}


	public function render(Request $request, $productType)
	{
		$plugin = $this->pluginManager->createInstance($productType);
		$filters = $plugin->getFilters();
		$filterValues = $this->extractFiltersFromRequest($request, $plugin);

		$defaultOffset = 0; // Start from the beginning
		$defaultLimit = 10; // Adjust the limit as needed

		// Use the hardcoded $defaultOffset and $defaultLimit instead of the non-existent methods
		$data = $plugin->getQuery($filterValues, $defaultOffset, $defaultLimit);

		$headers = $plugin->getHeaders();

		$productSearch = new ProductSearch('Stock Search', $productType);
		return $productSearch->render($data, $headers, $filters);
	}


	private function extractFiltersFromRequest(Request $request, $plugin)
	{
		$filters = $plugin->getFilters();
		$filterValues = [];

		foreach (array_keys($filters) as $filterKey) {
			$filterValues[$filterKey] = $request->query->get($filterKey, null);
		}

		return $filterValues;
	}

}



