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

	public function render(Request $request, $productType) {

		$pluginManager = $this->pluginManager;
		$definitions = $pluginManager->getDefinitions();
		\Drupal::logger('stockserach controller definitions')->debug('Available plugin definitions: ' . print_r(array_keys($definitions), TRUE));
		// Initialize filters from request parameters
		$filters = $this->initializeFiltersFromRequest($request);

		if (isset($definitions[$productType])) {
			$searchPlugin = $pluginManager->createInstance($productType, []);

			// Use the plugin to get query, filters, headers, etc.
			$data = $searchPlugin->getQuery($filters, 0, 10);
			$headers = $searchPlugin->getHeaders();

			// Instantiate ProductSearch and build form array
			//$productSearch = new \Drupal\athex_d_products\AthexRendering\ProductSearch('Your Title', []);
			//$searchForm = $productSearch->getSearchFormRA(); // This assumes getSearchFormRA() returns a form array as the method is private i need to allow access from external functions

			$productSearch = new \Drupal\athex_d_products\AthexRendering\ProductSearch('Your Title', []);
			$searchForm = $productSearch->getSearchFormRA(); // Use the new public method intead the getSearchFormRA

			//var_dump(array_keys(\Drupal::service('plugin.manager.product_search')->getDefinitions()));


			// Ensure data is not empty before proceeding
			if (!empty($data)) {
				$dataTable = new DataTable($headers, $data);

				return [
					'#theme' => 'product_search',
					'#page_title' => $this->t('Your Page Title'),
					'#search_form' => $searchForm,
					'#data' => $dataTable->render(),
					'#pager' => ['#type' => 'pager'],
				];
			} else {
				$this->messenger->addMessage($this->t('No data found.'), 'warning');
				return [];
			}
		} else {
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
	/*private function getTableHeaders()
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
	}*/

}

