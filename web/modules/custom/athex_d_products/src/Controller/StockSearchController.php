<?php


namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\athex_d_products\Service\StockDataService;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\HttpFoundation\Response;
/*
class StockSearchController extends ControllerBase
{
	protected $dataService;
	protected $productSearch;

	public function __construct(StockDataService $dataService, ProductSearch $productSearch, MessengerInterface $messenger)
	{
		$this->dataService = $dataService;
		$this->productSearch = $productSearch;
		$this->messenger = $messenger;
	}

	public static function create(ContainerInterface $container)
	{
		return new static(
			$container->get('athex_d_products.stock_data'),
			$container->get('athex_d_products.product_search')
		);
	}



*/

class StockSearchController extends ControllerBase {
	protected $dataService;
	protected $pluginManager;

	public function __construct(StockDataService $dataService, MessengerInterface $messenger, $pluginManager) {
		$this->dataService = $dataService;
		$this->messenger = $messenger;
		$this->pluginManager = $pluginManager;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data'),
			$container->get('messenger'),
			$container->get('plugin.manager.product_search') // Get the plugin manager
		);
	}
	/*
		public function render(Request $request)
		{
			\Drupal::logger('athex_d_products_controller sos')->notice('Query Parameters: @params', ['@params' => json_encode($request->query->all())]);

			// Retrieve all query parameters
			$queryParameters = $request->query->all();

			// Extract the 'market' parameter, ensuring it's treated as an array
			//$market = isset($queryParameters['market']) ? array_filter($queryParameters['market']) : [];// this is not needed for dropdown filters on markets

			// Prepare the rest of the filters
			$searchValue = $request->query->get('search_value', '');
			$minPrice = $request->query->get('minPrice', null);
			$maxPrice = $request->query->get('maxPrice', null);
			$market = $request->query->get('market', '');
			$letterFilter = $request->query->get('letter', ''); // Retrieve the selected letter

			// Prepare filters array including the 'market' array + letters
			$filters = [
				'searchValue' => $searchValue,
				'market' => $market,
				'minPrice' => $minPrice,
				'maxPrice' => $maxPrice,
				'letterFilter' => $letterFilter != 'All' ? $letterFilter : null, // Add the letter filter, exclude 'All'
			];
			// Fetch data based on filters
			$data = $this->dataService->search($filters, 0, 10);
			\Drupal::logger('SOS filters-1')->notice(print_r($data, TRUE));
			$dataTable = new DataTable($this->getTableHeaders(), $data);
			\Drupal::logger('SOS filters')->notice(print_r($filters, TRUE));

			// Render the search form and table
			return $this->productSearch->render($dataTable);
		}*/
	/*new plugin method */
	/*public function render(Request $request, $productType = 'stock_search') {
		$pluginManager = \Drupal::service('plugin.manager.product_search');
		//$plugin_manager = \Drupal::service('plugin.manager.product_search');
		$plugins = $plugin_manager->getDefinitions();
		//var_dump($plugins); // If you have Devel module installed, or use any other debugging method.

		$searchPlugin = $pluginManager->createInstance($productType);

		// Use the plugin to get query, filters, headers, etc.
		$data = $searchPlugin->getQuery($filters, 0, 10);
		$headers = $searchPlugin->getHeaders();
		$dataTable = new DataTable($headers, $data);

		// Render the search form and table
		return $this->productSearch->render($dataTable);
	}
*/
	public function render(Request $request, $productType )
	{
		$pluginManager = \Drupal::service('plugin.manager.product_search');
		$definitions = $pluginManager->getDefinitions();
		dump($definitions); // Use Drupal's dump() function or any other debugging tool you prefer

		// Initialize filters from request parameters
		$filters = $this->initializeFiltersFromRequest($request);

		if ($pluginManager->hasDefinition($productType)) {
			$searchPlugin = $pluginManager->createInstance($productType);

			// Use the plugin to get query, filters, headers, etc.
			$data = $searchPlugin->getQuery($filters, 0, 10);
			$headers = $searchPlugin->getHeaders();
			$dataTable = new DataTable($headers, $data);

			// Render the search form and table
			return $this->productSearch->render($dataTable);
		} else {
			// Plugin definition does not exist
			return new Response('The requested search plugin does not exist.', 404);
		}
	}


	private function initializeFiltersFromRequest(Request $request)
	{
		// Extract and return filters from the request object
		// Implement the logic to extract filters based on your application's requirements
		// For example:
		$filters = [
			'searchValue' => $request->query->get('search_value', ''),
			// Add more filters as needed
		];

		return $filters;
	}


	/*new method */
	private function getTableHeaders()
	{
		return [
			['data' => $this->t('Symbol'), 'field' => 'Symbol'],
			['data' => $this->t('ISIN'), 'field' => 'ISIN'],
			['data' => $this->t('Issuer'), 'field' => 'Issuer'],
			['data' => $this->t('Market'), 'field' => 'Market'],
			['data' => $this->t('Last Price'), 'field' => 'Last Price'],
			['data' => $this->t('Last Trading Date'), 'field' => 'Last Trading Date'],
			['data' => $this->t('Percentage'), 'field' => 'Percentage'],
		];
	}
}

