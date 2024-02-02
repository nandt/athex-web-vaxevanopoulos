<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\ProductsTable;
use Drupal\athex_inbroker\Service\ApiDataService;
use Drupal\athex_d_mde\AthexRendering\Helpers;
use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

class IndicesOverviewTablesService {

	use StringTranslationTrait;

	protected $config;

	/**
	 * The SisDbDataService service.
	 */
	protected $sisDbDataService;


	protected $api;
	protected $containers;

	private $pills = [
		'Risers',
		'Fallers',
		'Most Active'
	];

	/*public function __construct(
		ApiDataService $api,
		SisDbDataService $sisDbDataService, // Add SisDbDataService
		IndicesOverviewService $containers
	) {
		$this->api = $api;
		$this->sisDbDataService = $sisDbDataService; // correct property assignment
		$this->containers = $containers;
	}
	*/

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		SisDbDataService $sisDbDataService,
		IndicesOverviewService $indicesOverviewService,
		ApiDataService $apiDataService,
		LanguageManagerInterface $languageManager,
		ConfigFactoryInterface $configFactory
	) {
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->sisDbDataService = $sisDbDataService;
		$this->indicesOverviewService = $indicesOverviewService;
		//$this->apiDataService = $apiDataService;
		$this->api = $apiDataService; // updated this line
		$this->languageManager = $languageManager;
		$this->config = $configFactory->get('athex_d_mde.indicessettings');
	}




	/*private function getSubProductsTableRA($seldSymbol, $seldTable) {
		//TODO: get data from API
		return (new ProductsTable([
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, Helpers::renderDelta(-97.39), Helpers::renderDelta(-1.3, ' %')]
		]))->render();
	}
*/

	/*private function getSubProductsTableRA($seldSymbol, $seldTable) {
		$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
            JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
            JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
            WHERE hi.TICKER_EN = :gdValue";

		$data = $this->sisDbDataService->fetchAllWithParams($sql, [':gdValue' => $seldSymbol]);

		$tableRows = [];
		foreach ($data as $rowData) {
			$column1 = $rowData["column1"];
			$column2 = $rowData["column2"];
			$tableRows[] = [$column1, $column2, Helpers::renderDelta($column1), Helpers::renderDelta($column2, ' %')];
		}

		return (new ProductsTable($tableRows))->render();
	}
*/


	/*private function getSubProductsTableRA($seldSymbol, $seldTable) {
		$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
            JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
            JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
            WHERE hi.TICKER_EN = :gdValue";

		$data = $this->sisDbDataService->fetchAllWithParams($sql, [':gdValue' => $seldSymbol]);

		$tableRows = [];
		foreach ($data as $rowData) {
			$ticker_en = $rowData['TICKER_EN'];
			$apiData = $this->api->callDelayed('Info', ['code' => $ticker_en, 'format' => 'json']);
			var_dump($data);
			//$apiData = $this->api->getApiDataByTicker($ticker_en); // This is hypothetical, replace it with your actual API call method
			$column2 = $apiData["price"]; // Replace "column2" with the actual key in the API response
			$column3 = $apiData["column3"]; // Replace "column3" with the actual key in the API response
			$tableRows[] = [$ticker_en, $column2, Helpers::renderDelta($column2), Helpers::renderDelta($column3, ' %')];
		}

		return (new ProductsTable($tableRows))->render();
	}
*/
/*
	private function getSubProductsTableRA($seldSymbol, $seldTable) {
		$seldSymbol = $sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
            JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
            JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
            WHERE hi.TICKER_EN = :gdValue";

$codes =
		$apiData = $this->api->callDelayed('Info', ['code' => $codes, 'format' => 'json']);
		$data = $this->sisDbDataService->fetchAllWithParams($sql, [':gdValue' => $seldSymbol]);
		var_dump($data);
		$tableRows = [];
		foreach ($data as $rowData) {
			$ticker_en = $rowData['TICKER_EN'];
			$apiData = $this->api->callDelayed('Info', ['code' => $codes, 'format' => 'json']);
			//$items = $this->api->callDelayed('Info', ['code' => $codes]);
			var_dump($apiData);
			if (isset($apiData[0])) {
				$apiData = $apiData[0]; // Assuming the API returns an array of results, we take the first one
				$price = $apiData['price'] ?? 'Data not available';
				$change = $apiData['pricePrevClosePriceDelta'] ?? 'Data not available';

				// Construct a row for the table
				$tableRows[] = [
					$ticker_en, // Ticker symbol
					$price, // Price from the API
					$change, // Price change from the API
					// Additional columns as needed...
				];
			} else {
				// Handle the case where API response is not as expected
				$tableRows[] = [$ticker_en, 'API data not available', 'API data not available'];
			}
		}

		return (new ProductsTable($tableRows))->render();
	}*/

/*new test code*/

	/*public function getSubProductsTableRA($query, $type) {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$resultsByIndex = [];
		$apiDataCollection = [];
		$table = [];

		foreach ($gdValues as $gdValue) {
			//$data = $this->sisDbDataService->query($query, [':gdValue' => $gdValue], 'fetchAll');
			$data = $this->sisDbDataService->fetchAllWithParams($query, [':gdValue' => $gdValue]);

			foreach ($data as $entry) {
				if (isset($entry['TICKER_EN'])) {
					$apiData = $this->api->callDelayed('Info', ['code' => $entry['TICKER_EN'], 'format' => 'json']);
					$apiDataCollection[] = $apiData;
				}
			}

			$resultsByIndex[$gdValue] = $apiDataCollection;
		}

		foreach ($resultsByIndex as $gdValue => $data) {
			$rowData = [$gdValue];

			foreach ($data as $entry) {
				$rowData[] = $entry; // assuming the $entry is what you present in each column of the row, replace as needed
			}

			$table[] = $rowData;
		}

		return $table;
	}
*/

	/*new test*/


	/*
	public function getSubProductsTableRA($data)
	{
		$gdValue = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$resultsByIndex = [];
		$apiDataCollection = [];
		$table = [];
		/*$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
*

		$data = $this->sisDbDataService->fetchAllWithParams($data);
		$resultsByIndex[$gdValue] = $this->sisDbDataService->fetchAllWithParams($sql, [':gdValue' => $gdValue]);
		var_dump($resultsByIndex);
		foreach ($gdValues as $gdValue) {
			//$data = $this->sisDbDataService->fetchAllWithParams($query, [':gdValue' => $gdValue]);


			var_dump($data);
			foreach ($data as $entry) {
				if (isset($entry['TICKER_EN'])) {
					$apiData = $this->api->callDelayed('Info', ['code' => $entry['TICKER_EN'], 'format' => 'json'])[0];

					if (is_array($apiData)) {
						$resultsByIndex[$gdValue][] = [
							'symbol' => $apiData['instrSysName'],
							'value' => $apiData['price'],
							'since_open_value' => $apiData['pricePrevPriceDelta'],
							'since_open_percentage' => $apiData['pricePrevPricePDelta'],
							'since_close_value' => $apiData['pricePrevClosePriceDelta'],
							'since_close_percentage' => $apiData['pricePrevClosePricePDelta']
						];
					}

				}

			}

		}

		foreach ($resultsByIndex as $gdValue => $rows) {
			foreach ($rows as $rowData) {
				$table[] = [
					$rowData['symbol'],
					$rowData['value'],
					Helpers::renderDelta($rowData['since_open_value']),
					Helpers::renderDelta($rowData['since_open_percentage'], '%'),
					Helpers::renderDelta($rowData['since_close_value']),
					Helpers::renderDelta($rowData['since_close_percentage'], '%'),
				];
			}
		}

		return (new ProductsTable($table))->render();
	}
*/
/*
		public function getSubProductsTableRA($data) {
			$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
			$resultsByIndex = [];
			$apiDataCollection = [];
			$table = [];

			foreach ($gdValues as $gdValue) {
				// Assuming $data is an array of data fetched from the database
				if (is_array($data)) {
					foreach ($data as $entry) {
						if (isset($entry['TICKER_EN'])) {
							$apiData = $this->api->callDelayed('Info', ['code' => $entry['TICKER_EN'], 'format' => 'json'])[0];

							if (is_array($apiData)) {
								$resultsByIndex[$gdValue][] = [
									'symbol' => $apiData['instrSysName'],
									'value' => $apiData['price'],
									'since_open_value' => $apiData['pricePrevPriceDelta'],
									'since_open_percentage' => $apiData['pricePrevPricePDelta'],
									'since_close_value' => $apiData['pricePrevClosePriceDelta'],
									'since_close_percentage' => $apiData['pricePrevClosePricePDelta'],
								];
							}
						}
					}
				}
			}

			// Convert the collected data into a format suitable for rendering
			foreach ($resultsByIndex as $indexData) {
				foreach ($indexData as $rowData) {
					$table[] = [
						$rowData['symbol'],
						$rowData['value'],
						Helpers::renderDelta($rowData['since_open_value']),
						Helpers::renderDelta($rowData['since_open_percentage'], '%'),
						Helpers::renderDelta($rowData['since_close_value']),
						Helpers::renderDelta($rowData['since_close_percentage'], '%'),
					];
				}
			}

			return (new ProductsTable($table))->render();
		}
		*/


/*	public function getSubProductsTableRA($gdValue) {

		$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
        JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
        JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
        WHERE hi.TICKER_EN = :gdValue";
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
// Define your parameters in an associative array
		$params = [':gdValue' => $gdValues]; // Replace 'YourGDValueHere' with the actual GD value you want to query
		//$indices = explode(',', 'params');
// Now call fetchAllWithParams with the SQL string and parameters array
		$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
		var_dump($data);

		//$data = $this->sisDbDataService->fetchAllWithParams($data);
		$tableRows = [];

		// Check if $data is an array and not empty
		if (is_array($data) && !empty($data)) {
			foreach ($data as $entry) {
				// Ensure that each $entry has the 'TICKER_EN' key
				if (isset($entry['TICKER_EN'])) {
					$tickerEn = $entry['TICKER_EN'];

					// Call the API with the ticker symbol
					$apiData = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);
var_dump($apiData);
					// Check if $apiData is an array and contains expected keys
					if (is_array($apiData) && isset($apiData[0]) && is_array($apiData[0])) {
						$apiResponse = $apiData[0];

						// Now you can safely access the $apiResponse array
						if (isset($apiResponse['price'], $apiResponse['pricePrevPriceDelta'])) {
							// Construct your table row
							$row = [
								$tickerEn,
								$apiResponse['price'],
								Helpers::renderDelta($apiResponse['pricePrevPriceDelta']),
								// Add more elements as needed...
							];
							$tableRows[] = $row;
						}
					}
				}
			}
		}

		// Now, use $tableRows to construct your ProductsTable and return its render output
		return (new ProductsTable($tableRows))->render();
	}
*/
	/*public function getSubProductsTableRA() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			// Prepare your SQL statement with a placeholder for the dynamic value
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";

			// Bind the current $gdValue to the placeholder in your SQL statement
			$params = [':gdValue' => $gdValue];

			// Fetch data from the database
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
var_dump($data);
			// Process each row of data
			foreach ($data as $entry) {
				if (isset($entry['TICKER_EN'])) {
					$tickerEn = $entry['TICKER_EN'];

					// Example API call with the ticker symbol - adjust according to your actual API call and data structure
					$apiData = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);

					// Process the API data and add rows to $tableRows
					// (Ensure you handle the API response correctly based on its structure)
					if (isset($apiData['price'], $apiData['pricePrevPriceDelta'])) {
						$tableRows[] = [
							$tickerEn,
							$apiData['price'],
							Helpers::renderDelta($apiData['pricePrevPriceDelta']),
							// Add more columns as needed
						];
					}
				}
			}
		}

		// Use $tableRows to construct your ProductsTable and return its rendered output
		return (new ProductsTable($tableRows))->render();
	}

*/

	/*public function getSubProductsTableRA() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);

			foreach ($data as $entry) {
				if (isset($entry['TICKER_EN'])) {
					$tickerEn = $entry['TICKER_EN'];
					// Make the API call
					$apiData = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);

					// Assuming the API returns a response in the expected format, process the data
					// You might need to adjust this part based on the actual structure of your API response
					if (!empty($apiData) && isset($apiData['price'], $apiData['pricePrevPriceDelta'])) {
						$tableRows[] = [
							'symbol' => $tickerEn,
							'price' => $apiData['price'],
							'change' => $apiData['pricePrevPriceDelta'],
							// You can add more columns based on the API response
						];
					}
				}
			}
		}

		// Construct and return the ProductsTable with the gathered data
		return (new ProductsTable($tableRows))->render();
	}
*//*
	public function getSubProductsTableRA() {
		/*$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];

		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
		//	var_dump($data);
			//foreach ($data as $entry) {
			//	if (isset($entry['TICKER_EN'])) {
			//		$tickerEn = $entry['TICKER_EN'];
					// Make the API call
			//		$apiData = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);
					//var_dump($apiData); // add this line
					//error_log(json_encode($apiData));
			foreach ($data as $entry) {
				if (isset($entry['TICKER_EN'])) {
					$tickerEns[] = $entry['TICKER_EN'];
				}
			}

			// Separate TICKER_ENs with commas for the API call
			$indicesString = implode(',', $tickerEns);
			$apiData = $this->api->callDelayed('Info', ['code' => $indicesString, 'format' => 'json']);
					if (!empty($apiData) && isset($apiData['price'], $apiData['pricePrevPriceDelta'])) {
						$tableRows[] = [
							'symbol' => $tickerEn,
							'price' => $apiData['price'],
							'change' => $apiData['pricePrevPriceDelta'],
							// You can add more columns based on the API response
						];
					}
				}
			}


		// Construct and return the ProductsTable with the gathered data
		//return (new ProductsTable($tableRows))->render();

		var_dump($tableRows);
		$result = (new ProductsTable($tableRows))->render();
		var_dump($result);
		return $result;
	}*/
	/*public function getSubProductsTableRA() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			$tickerEns = [];
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
            JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
            JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
            WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
var_dump($data);

			$indicesString = $this->config->get('indices') ?: 'GD.ATH,FTSE.ATH,ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH';
			$indices = explode(',', $indicesString);
			$apiResponse = $this->api->callDelayed('Info', ['code' => $indicesString, 'format' => 'json']);
			$processedData = [];

			foreach ($apiResponse as $item) {
				if (is_array($item) && in_array($item['instrSysName'], $indices)) {
					$processedData[$item['instrSysName']] = [
						'symbol' => $item['instrSysName'],
						'value' => $item['price'],
						'since_open_value' => $item['pricePrevPriceDelta'],
						'since_open_percentage' => $item['pricePrevPricePDelta'],
						'since_close_value' => $item['pricePrevClosePriceDelta'],
						'since_close_percentage' => $item['pricePrevClosePricePDelta']
					];
				}
			}
			var_dump($processedData);


		}

		$result = (new ProductsTable($tableRows))->render();
		//var_dump($result);
		return $result;

	}
*/


	/*
	 *
	 public function getSubProductsTableRA() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			// Fetch ticker symbols from the database
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);

			// For each ticker symbol, call the API and construct a table row
			foreach ($data as $entry) {
				$tickerEn = $entry['TICKER_EN'] . '.ATH';
				$apiResponse = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);
//var_dump($apiResponse);
				// Check if the API response is valid and contains the necessary data
				if (isset($apiResponse[0]) && is_array($apiResponse[0])) {
					$apiData = $apiResponse[0];

					$tableRows[] = [
						'symbol' => $tickerEn,
						'value' => $apiResponse["price"] ,
						'since_open_value' => $apiData['pricePrevPriceDelta'] ?? 'N/A',
						'since_open_percentage' => $apiData['pricePrevPricePDelta'] ?? 'N/A',
						'since_close_value' => $apiData['pricePrevClosePriceDelta'] ?? 'N/A',
						'since_close_percentage' => $apiData['pricePrevClosePricePDelta'] ?? 'N/A',

						'symbol' => $apiData['instrSysName'] ?? 'N/A',
						'name' => $apiData['instrName'] ?? 'N/A',
						'value' => $apiData['price'] ?? 'N/A',
						'change_euro' => $apiData['pricePrevClosePriceDelta'] ?? 'N/A',
						'change_percent' => $apiData['pricePrevClosePricePDelta'] ?? 'N/A',

					];
				} else {
					// Handle the case where API response is not as expected
					$tableRows[] = [$tickerEn, 'API data not available', 'N/A', 'N/A', 'N/A', 'N/A'];
				}
			}
		}

		// Render the table with the constructed rows
		return (new ProductsTable($tableRows))->render();
	}


	*/

	public function getSubProductsTableRA() {
		$firstResponseLogged = false; // Initialize the variable at the start of the function
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);

			foreach ($data as $entry) {
				$tickerEn = $entry['TICKER_EN'] . '.ATH';
				$apiResponse = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);

				// Log the first API response for inspection
				if (!$firstResponseLogged) {
					$this->logger->info(print_r($apiResponse, true));
					$firstResponseLogged = true;
				}

				if (isset($apiResponse['instrSysName'])) { // Ensure there's a symbol in the response
					$tableRows[] = [
						'symbol' => $apiResponse['instrSysName'] ?? 'N/A',
						'name' => $apiResponse['instrName'] ?? 'N/A',
						'value' => isset($apiResponse['price']) ? $apiResponse['price'] : 'N/A', // Check explicitly for price to handle 0 or null
						'change_euro' => isset($apiResponse['pricePrevClosePriceDelta']) ? $apiResponse['pricePrevClosePriceDelta'] : 'N/A',
						'change_percent' => isset($apiResponse['pricePrevClosePricePDelta']) ? $apiResponse['pricePrevClosePricePDelta'] : 'N/A',
					];
				} else {
					// Handle the case where API response does not include the necessary symbol information
					$tableRows[] = [$tickerEn, 'Symbol data not available', 'N/A', 'N/A', 'N/A'];
				}
			}
		}

		return (new ProductsTable($tableRows))->render();
	}









	/*public function getBlockRA($seldTable = null) {
		$symbols = [''];
		//$symbols = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		foreach ($symbols as $seldSymbol) {
			//$container = $this->containers->createContainer();
			$container = $this->indicesOverviewService->createContainer(); // updated
			$selectedSymbol = $seldSymbol; //using the initial symbol
			$containers[] = $container->render(
				$this->getSubProductsRA($selectedSymbol, $seldTable)
			);
		}
		return $containers;
	}
*/

	public function getBlockRA($seldTable = null)
	{
		$blockContent = $this->getSubProductsRA(null, $seldTable); // Passing null for the symbol argument.
		$container = $this->indicesOverviewService->createContainer();
		$renderedBlock = $container->render($blockContent);

		return [$renderedBlock];
	}
	private function getSubProductsPillsRA($seldTable) {
		return (new BsNav($this->pills, $seldTable, 'pills'))->render();
	}

	private function getSubProductsRA($seldSymbol, $seldTable = null) {
		if ($seldTable == null)
			$seldTable = $this->pills[0];

		return [
			'#type' => 'container',
			$this->getSubProductsPillsRA($seldTable),
			$this->getSubProductsTableRA($seldSymbol, $seldTable),
			[
				'#type' => 'link',
				'#title' => $this->t('Explore More'),
				'#url' => \Drupal\Core\Url::fromUri('internal:#')
			]
		];
	}

    /*public function getBlockRA($seldTable = null)
    {
        $container = $this->containers->createContainer();
        $selectedSymbol = $container->selectedData['symbol'];

        return $container->render(
            $this->getSubProductsRA($selectedSymbol, $seldTable)
        );
    }*/
}
