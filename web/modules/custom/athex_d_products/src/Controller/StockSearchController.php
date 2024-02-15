<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\athex_d_products\AthexRendering\ProductSearch; // Add this line
//use Drupal\athex_d_products\AthexRendering\DataTable;
use Drupal\athex_d_mde\AthexRendering\DataTable;

class StockSearchController extends ControllerBase {
	protected $pluginManager;
	protected $messenger;

	public function __construct($pluginManager, MessengerInterface $messenger) {
		$this->pluginManager = $pluginManager;
		$this->messenger = $messenger;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('plugin.manager.product_search'),
			$container->get('messenger')
		);
	}

	/*public function render(Request $request, $productType) {
		$plugin = $this->pluginManager->createInstance($productType);

		if (!$plugin) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
				$this->t('The product type @productType does not exist.', ['@productType' => $productType])
			);
		}

		$filterValues = $this->extractFiltersFromRequest($request, $productType);
		$data = $plugin->getQuery($filterValues, 0, 10);
		$headers = $plugin->getHeaders();

		//$productSearch = new ProductSearch($plugin->getLabel(), $filters);
		$productSearch = new ProductSearch('Stock Search', $filters);  // just for debugging

		$dataTable = new DataTable($headers, $data);

		return $productSearch->render($dataTable, $filterValues);
	}*/
	/*public function render(Request $request, $productType) {
		$plugin = $this->pluginManager->createInstance($productType);

		if (!$plugin) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
				$this->t('The product type @productType does not exist.', ['@productType' => $productType])
			);
		}

		// Ensure $filters is initialized from the plugin and is an array
		$filters = $plugin->getFilters();
		if (!is_array($filters)) {
			$filters = []; // Initialize as an empty array if not an array
		}

		$filterValues = $this->extractFiltersFromRequest($request, $plugin); // Pass the plugin instance to extract filters
		$data = $plugin->getQuery($filterValues, 0, 10);
		$headers = $plugin->getHeaders();

		// Initialize ProductSearch with the label and filters
		//$productSearch = new ProductSearch($plugin->getLabel(), $filters);
		$productSearch = new ProductSearch('Stock Search', $filters);  // just for debugging
		$dataTable = new DataTable($headers, $data);

		// Pass $filterValues and $headers to the render method
		return $productSearch->render($dataTable, $filterValues, $headers);
	}
*/
	/*public function render(Request $request, $productType) {
	$plugin = $this->pluginManager->createInstance($productType);

	// Fetch filters from the plugin
	$filterValues = $plugin->getFilters();

	// Fetch filter values from the request
	$filterValues = $this->extractFiltersFromRequest($request,$plugin);

	// Fetch query data and headers
	$data = $plugin->getQuery($filterValues, 0, 10);
	$headers = $plugin->getHeaders();

	// Initialize ProductSearch with the title and filters
	//$productSearch = new ProductSearch($plugin->getLabel(), $filters);
	$productSearch = new ProductSearch('Stock Search', $filterValues);  // just for debugging

	// Pass filterValues and headers to the render method
	return $productSearch->render(new DataTable($headers, $data), $filterValues, $headers);
}*/
	public function render(Request $request, $productType) {
		$plugin = $this->pluginManager->createInstance($productType);
		$filters = $plugin->getFilters(); // Fetch the filters from the plugin

		$filterValues = $this->extractFiltersFromRequest($request, $plugin);

		$data = $plugin->getQuery($filterValues, 0, 10);
		$headers = $plugin->getHeaders();

		$productSearch = new ProductSearch('Stock Search', $filters); // Pass the filters here
		$dataTable = new DataTable($headers, $data);

		return $productSearch->render($dataTable, $filterValues, $headers, $filters);

	}






	private function extractFiltersFromRequest(Request $request, $plugin) {
		$filters = $plugin->getFilters();
		$filterValues = [];

		foreach (array_keys($filters) as $filterKey) {
			$filterValues[$filterKey] = $request->query->get($filterKey, null);
		}

		return $filterValues;
	}





	/*private function extractFilterValuesFromRequest(Request $request, array $filterKeys) {
		$values = [];
		foreach ($filterKeys as $key) {
			$values[$key] = $request->query->get($key);
		}
		return $values;
	}*/
}


/*
	private function extractFiltersFromRequest(Request $request) {
		return [
			'searchValue' => $request->query->get('search_value', ''),
			'market' => $request->query->get('market', null),
			'minPrice' => $request->query->get('minPrice', null),
			'maxPrice' => $request->query->get('maxPrice', null),
			'letterFilter' => $request->query->get('letter', null),
		];
	}

}
*/

