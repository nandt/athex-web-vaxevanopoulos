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

class IndicesOverviewTablesService
{

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


	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		SisDbDataService              $sisDbDataService,
		IndicesOverviewService        $indicesOverviewService,
		ApiDataService                $apiDataService,
		LanguageManagerInterface      $languageManager,
		ConfigFactoryInterface        $configFactory
	)
	{
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


	/*public function getSubProductsTableRA() {
		$firstResponseLogged = false; // Initialize the variable at the start of the function
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$tableRows = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			    $params = [':gdValue' => $gdValue ];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
           // Count and log number of items returned for each gdValue
			$count = count($data);
			$this->logger->info("Count for {$gdValue}: {$count}");
			// end of count function
			foreach ($data as $entry) {
				$tickerEn = $entry['TICKER_EN'] . '.ATH';
				$apiResponse = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);
				//checks if api is null
				if ($apiResponse === null) {
					// Log an error message or handle this case however you prefer
					$this->logger->error("API response for {$tickerEn} is null");
					continue; // this skips the current iteration of the loop and moves on to the next entry
				}
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
*/


	public function getSubProductsTables()
	{
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$allTables = [];

		foreach ($gdValues as $gdValue) {
			// Fetch data from your database for this GD value
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);

			// Prepare tables for "Risers" and "Fallers" categories with common data structure
			$allTables[$gdValue]['Risers'] = $this->prepareTable($data, 'common');
			$allTables[$gdValue]['Fallers'] = $this->prepareTable($data, 'common');

			// Prepare table for "Most Active" category with specific data structure
			$allTables[$gdValue]['Most Active'] = $this->prepareTable($data, 'most_active');
		}

		// This will return an array of tables for each GD value and category
		return $allTables;
	}

	private function prepareTable($data, $type)
	{
		$tableRows = [];

		foreach ($data as $entry) {
			$tickerEn = $entry['TICKER_EN'] . '.ATH';
			$apiResponse = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);

			if ($apiResponse === null) {
				$this->logger->error("API response for {$tickerEn} is null");
				continue;
			}

			if ($type == 'most_active') {
				// Specific data structure for "Most Active"
				$tableRows[] = [
					'symbol' => $apiResponse['instrSysName'] ?? 'N/A',
					'value' => $apiResponse['price'] ?? 'N/A',
					'total_volume' => $apiResponse['totalInstrVolume'] ?? 'N/A',
				];
			} else {
				// Common data structure for "Risers" and "Fallers"
				$tableRows[] = [
					'symbol' => $apiResponse['instrSysName'] ?? 'N/A',
					'name' => $apiResponse['instrName'] ?? 'N/A',
					'value' => isset($apiResponse['price']) ? $apiResponse['price'] : 'N/A',
					'change_euro' => isset($apiResponse['pricePrevClosePriceDelta']) ? $apiResponse['pricePrevClosePriceDelta'] : 'N/A',
					'change_percent' => isset($apiResponse['pricePrevClosePricePDelta']) ? $apiResponse['pricePrevClosePricePDelta'] : 'N/A',
				];
			}
		}

		return (new ProductsTable($tableRows))->render();
	}


	public function getBlockRA($seldTable = null)
	{
		$blockContent = $this->getSubProductsTables(null, $seldTable); // Passing null for the symbol argument.
		$container = $this->indicesOverviewService->createContainer();
		$renderedBlock = $container->render($blockContent);

		return [$renderedBlock];
	}

	private function getSubProductsPillsRA($seldTable)
	{
		return (new BsNav($this->pills, $seldTable, 'pills'))->render();
	}

	/*private function getSubProductsTables($seldSymbol, $seldTable = null) {
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
	}*/


}
