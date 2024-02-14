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
	protected $filters; // Define the filters property

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

	/*public function render(Request $request, $productType) {
		$this->initializeFiltersFromRequest($request); // Initialize filters from request

		$pluginInstance = $this->getPluginInstance($productType);

		if (!$pluginInstance) {
			return new Response('The requested search plugin does not exist.', 404);
		}

		$data = $this->getPluginData($pluginInstance);

		if (empty($data)) {
			$this->messenger->addMessage($this->t('No data found.'), 'warning');
			return [];
		} else {
			return $this->buildResponse($data);
		}
	}*/
	public function render(Request $request, $productType) {
		// Get the plugin manager and check if the plugin exists
		$pluginManager = \Drupal::service('plugin.manager.product_search');
		$definitions = $pluginManager->getDefinitions();

		// Check if the productType exists as a plugin ID
		if (isset($definitions[$productType])) {
			// Plugin exists, proceed with rendering or fetching data
			return [
				'#markup' => $this->t('Hello, this is a test page for the product type: @productType', ['@productType' => $productType]),
			];
		} else {
			// Plugin does not exist, return a 404 response
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($this->t('The product type @productType does not exist.', ['@productType' => $productType]));
		}
	}

	private function initializeFiltersFromRequest(Request $request) {
		$this->filters = [
			'searchValue' => $request->query->get('search_value', ''),
			'letterFilter' => $request->query->get('letterFilter', null), // Initialize letterFilter
			// Add more filters as needed
		];
	}

	private function getPluginInstance($productType) {
		$definitions = $this->pluginManager->getDefinitions();

		if (isset($definitions[$productType])) {
			return $this->pluginManager->createInstance($productType, []);
		}

		return null;
	}

	private function getPluginData($pluginInstance) {
		return $pluginInstance->getQuery($this->filters, 0, 10);
	}

	private function buildResponse($data) {
		$headers = ['Symbol', 'ISIN', 'Issuer', 'Market', 'Last Price', 'Last Trading Date', 'Percentage']; // Define your headers here
		$dataTable = new DataTable($headers, $data);

		return [
			'#theme' => 'product_search',
			'#page_title' => $this->t('Your Page Title'),
			'#search_form' => $this->buildSearchForm(),
			'#data' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}

	private function buildSearchForm() {
		$productSearch = new ProductSearch('Your Title', []);
		return $productSearch->getSearchFormRA(); // Assuming getSearchFormRA returns a render array for the search form
	}
}
