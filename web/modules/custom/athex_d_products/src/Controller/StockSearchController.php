<?php
/*
namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_products\Service\StockDataService;


class StockSearchController extends ControllerBase {

	protected ProductSearch $search;
	protected StockDataService $data;

	private function getFilterRA($name) {
		return [
			'#type' => 'details',
			'#title' => $this->t($name)
		];
	}

	public function __construct(
		StockDataService $data
	) {
		$this->data = $data;
		$this->search = new ProductSearch(
			'Stock Search', [
				$this->getFilterRA('Market'),
				$this->getFilterRA('Industry'),
				$this->getFilterRA('Closing Price'),
				$this->getFilterRA('Date Range')
			]
		);
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data')
		);
	}

	public function render() {
		$data = $this->data->search(
			[],
			$this->search->getResultsOffset(),
			$this->search->getResultsLimit()
		);

		foreach ($data as &$row) {
			$row['symbol'] = [
				'#type' => 'link',
				'#title' => $row['symbol'],
				'#url' => \Drupal\Core\Url::fromRoute(
					'athex_d_products.product_profile',
					[
						'product_type' => 'stocks',
						'product_id' => $row['symbol']
					]
				)
			];
		}

		return $this->search->render(
			new DataTable(
				[
					[ 'field' => 'symbol',		'label' => 'Symbol',		'pinned' => true ],
					[ 'field' => 'company',		'label' => 'Company Name'	 ], // WARN: has css styles on .field--company
					[ 'field' => 'isin',		'label' => 'ISIN'			 ],
					[ 'field' => 'market',		'label' => 'Market'			 ],
					[ 'field' => 'last',		'label' => 'Last'			 ],
					[ 'field' => 'percent',		'label' => '%',				'pinned' => true ],
					[ 'field' => 'date_time',	'label' => 'Date / Time'	 ]
				],
				$data
			)
		);
	}
}
*/

/*that work apart the seach*/
/*
namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\athex_d_products\Service\StockDataService;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_mde\AthexRendering\DataTable;

class StockSearchController extends ControllerBase {
	protected $messenger;
	protected $dataService;

	public function __construct(StockDataService $dataService, MessengerInterface $messenger) {
		$this->dataService = $dataService;
		$this->messenger = $messenger;
	}



	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data'),
			$container->get('messenger')
		);
	}

	public function render(Request $request) {
		$this->messenger->addMessage('Render method called');

		// Define the table headers
		$header = [
			['data' => $this->t('Symbol')],
			['data' => $this->t('ISIN')],
			['data' => $this->t('Issuer')],
			['data' => $this->t('Market')],
			['data' => $this->t('Last Price')],
			['data' => $this->t('Last Trading Date')],
			['data' => $this->t('Percentage')],
		];


		// Get filters from request, e.g., search value
		$searchValue = $request->query->get('search', '');

		// Call the search method of data service with filters
		$data = $this->dataService->search(['searchValue' => $searchValue], 0, 10);

		// Construct the rows for the table
		$rows = [];
		foreach ($data as $item) {
			$rows[] = [
				'data' => [
					$item['Symbol'] ?? 'N/A', // Provide 'N/A' if 'Symbol' key does not exist
					$item['ISIN'] ?? 'N/A', // Provide 'N/A' if 'ISIN' key does not exist
					$item['Issuer'] ?? 'N/A', // Provide 'N/A' if 'Issuer' key does not exist
					$item['Market'] ?? 'N/A', // Provide 'N/A' if 'Market' key does not exist
					$item['Last Price'] ?? 'N/A', // Provide 'N/A' if 'Last Price' key does not exist
					$item['Last Trading Date'] ?? 'N/A', // Provide 'N/A' if 'Last Trading Date' key does not exist
					$item['Percentage'] ?? 'N/A', // Provide 'N/A' if 'Percentage' key does not exist
				]
			];
		}




		$build['stock_table'] = [
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $rows,
			'#empty' => $this->t('No stocks found'),
		];


		// Return the render array for the table
		return $build;

	}

}*/

/*new try*/
namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\athex_d_products\Service\StockDataService;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Messenger\MessengerInterface;

class StockSearchController extends ControllerBase {
	protected $dataService;
	protected $productSearch;


	public function __construct(StockDataService $dataService, ProductSearch $productSearch, MessengerInterface $messenger)
	{
		$this->dataService = $dataService;
		$this->productSearch = $productSearch;
		$this->messenger = $messenger;
	}
	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data'),
			$container->get('athex_d_products.product_search')
		);
	}

	/*public function render(Request $request) {
		// Get filters from request, e.g., search value
		$searchValue = $request->query->get('search', '');

		// Call the search method of data service with filters
		$data = $this->dataService->search(['searchValue' => $searchValue], 0, 10);
		//var_dump($data);
		// Convert the data into a format suitable for DataTable rendering
		$dataTable = new DataTable($this->getTableHeaders(), $data);

		// Use ProductSearch to render the search form and table
		$renderArray = $this->productSearch->render($dataTable);

		// Return the render array for the search form and table
		return $renderArray;
	}
*/
/*
	public function render(Request $request) {
		$searchValue = $request->query->get('search', '');

		// Pass $searchValue to the search method
		$data = $this->dataService->search(['searchValue' => $searchValue], 0, 10);

		// Convert the data into a format suitable for DataTable rendering
		$dataTable = new DataTable($this->getTableHeaders(), $data);

		// Use ProductSearch to render the search form and table
		$renderArray = $this->productSearch->render($dataTable);

		return $renderArray;
	}
*/


/*
 *
 *
 *

public function render(Request $request) {
    // Capture search_value from query parameters
    $searchValue = $request->query->get('search_value', ''); // Default to empty string if not set

    // Pass $searchValue to the search method
    $data = $this->dataService->search(['searchValue' => $searchValue], 0, 10);

    // Rest of your render method...
}


 */



	public function render(Request $request) {

		      $searchValue = $request->query->get('search_value', ''); // Default to empty string if not set
			//$selectedLetter = $request->query->get('letter', ''); // Retrieve selected letter from query parameters

			// Pass both search value and selected letter to the search method
			/*$data = $this->dataService->search([
				'searchValue' => $searchValue,
				'selectedLetter' => $selectedLetter
			], 0, 10);
			*/
		$data = $this->dataService->search(['searchValue' => $searchValue], 0, 10);

		$dataTable = new DataTable($this->getTableHeaders(), $data);
		$renderArray = $this->productSearch->render($dataTable);

		return $renderArray;
	}


	// In your StockSearchController.php or wherever you define the table structure
	private function getTableHeaders() {
		return [
			['data' => $this->t('Symbol'), 'field' => 'Symbol'], // Capitalize 'Symbol'
			['data' => $this->t('ISIN'), 'field' => 'ISIN'], // Capitalize 'ISIN'
			['data' => $this->t('Issuer'), 'field' => 'Issuer'], // Capitalize 'Issuer'
			['data' => $this->t('Market'), 'field' => 'Market'], // Capitalize 'Market'
			['data' => $this->t('Last Price'), 'field' => 'Last Price'], // Capitalize 'Last Price'
			['data' => $this->t('Last Trading Date'), 'field' => 'Last Trading Date'], // Capitalize 'Last Trading Date'
			['data' => $this->t('Percentage'), 'field' => 'Percentage'] // Capitalize 'Percentage'
		];
	}

}
