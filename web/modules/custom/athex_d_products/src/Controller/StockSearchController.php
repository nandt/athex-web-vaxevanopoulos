<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\athex_d_products\AthexRendering\ProductSearch; // Add this line
use Drupal\athex_d_products\AthexRendering\DataTable;


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
	public function render(Request $request, $productType) {
		$plugin = $this->pluginManager->createInstance($productType);

		if (!$plugin) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
				$this->t('The product type @productType does not exist.', ['@productType' => $productType])
			);
		}

		$filterValues = $this->extractFiltersFromRequest($request, $productType);
		$data = $plugin->getQuery($filterValues, 0, 10);
		$headers = $plugin->getHeaders();

		// Assuming $plugin->getFilters() returns an array of filters, which will be used as secondary filters.
		// If getFilters() might return null, ensure to provide an empty array as a fallback.
		$filters = $plugin->getFilters() ?? [];

		// Pass $filters as the second argument for ProductSearch constructor.
		//$productSearch = new ProductSearch($plugin->getLabel(), $filters);
		$productSearch = new ProductSearch('Stock Search', $filters);  // just for debugging
		$dataTable = new DataTable($headers, $data);

		// Pass $filterValues as the second argument to the render method if needed.
		return $productSearch->render($dataTable, $filterValues, $headers);
	}



	private function extractFiltersFromRequest(Request $request, $productType) {
		$plugin = $this->pluginManager->createInstance($productType);

		if (!$plugin) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
				$this->t('The product type @productType does not exist.', ['@productType' => $productType])
			);
		}

		$filters = $plugin->getFilters();
		$filterValues = [];

		foreach (array_keys($filters) as $filterKey) {
			$filterValues[$filterKey] = $request->query->get($filterKey);
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

