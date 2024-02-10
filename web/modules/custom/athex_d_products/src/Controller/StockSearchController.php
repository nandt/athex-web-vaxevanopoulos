<?php


namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\athex_d_products\Service\StockDataService;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Messenger\MessengerInterface;

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

		// Prepare filters array including the 'market' array
		$filters = [
			'searchValue' => $request->query->get('search_value', ''),
			'market' => $request->query->get('market', null),
			'minPrice' => $request->query->get('minPrice', null),
			'maxPrice' => $request->query->get('maxPrice', null),
		];
		// Fetch data based on filters
		$data = $this->dataService->search($filters, 0, 10);
		\Drupal::logger('SOS filters-1')->notice(print_r($data, TRUE));
		$dataTable = new DataTable($this->getTableHeaders(), $data);
		\Drupal::logger('SOS filters')->notice(print_r($filters, TRUE));

		// Render the search form and table
		return $this->productSearch->render($dataTable);
	}

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

