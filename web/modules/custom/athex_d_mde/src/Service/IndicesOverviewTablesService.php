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
	protected $configFactory;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		SisDbDataService              $sisDbDataService,
		IndicesOverviewService        $indicesOverviewService,
		ApiDataService                $apiDataService,
		LanguageManagerInterface      $languageManager,
		ConfigFactoryInterface        $configFactory // Ensure this is passed in
	)
	{
		if ($configFactory === null) {
			drupal_set_message('ConfigFactory is null');
		}
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->sisDbDataService = $sisDbDataService;
		$this->indicesOverviewService = $indicesOverviewService;
		$this->api = $apiDataService;
		$this->languageManager = $languageManager;
		$this->configFactory = $configFactory; // Assign to class property
		//$this->gdValues = explode(',', $this->configFactory->get('athex_d_mde.settings')->get('gd_values'));
		$gdValuesString = $this->configFactory->get('athex_d_mde.settings')->get('gd_values');
		$this->gdValues = $gdValuesString ? explode(',', $gdValuesString) : [];

	}


	public function getSubProductsTables()
	{
		$allTables = [];

		foreach ($this->gdValues as $gdValue) {
			// Fetch data from your database for this GD value
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
            JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
            JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
            WHERE hi.TICKER_EN = :gdValue";
			$params = [':gdValue' => $gdValue];
			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);
//var_dump($data);
			// Prepare tables for "Risers" and "Fallers" categories with common data structure
			$allTables[$gdValue]['Risers'] = $this->prepareTable($data, 'common');
			$allTables[$gdValue]['Fallers'] = $this->prepareTable($data, 'common');

			// Prepare table for "Most Active" category with specific data structure
			$allTables[$gdValue]['Most Active'] = $this->prepareTable($data, 'most_active');
		}

		// This will return an array of tables for each GD value and category
		return $allTables;
	}

	/*private function prepareTable($data, $type)
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
	}*/
	private function prepareTable($data, $type)
	{
		$tableRows = [];

		foreach ($data as $entry) {
			$tickerEn = $entry['TICKER_EN'] . '.ATH';
			$apiResponse = $this->api->callDelayed('Info', ['code' => $tickerEn, 'format' => 'json']);

			// Check for missing keys in the API response
			$requiredKeys = ['instrSysName', 'price']; // Add more keys as needed
			foreach ($requiredKeys as $key) {
				if (!array_key_exists($key, $apiResponse)) {
					$this->logger->error("Missing {$key} in API response for {$tickerEn}");
					// Set a default value or take other actions as needed
					$apiResponse[$key] = 'N/A'; // Setting a default value for missing key
				}
			}

			if ($type == 'most_active') {
				// Specific data structure for "Most Active"
				$tableRows[] = [
					'symbol' => $apiResponse['instrSysName'],
					'value' => $apiResponse['price'],
					'total_volume' => $apiResponse['totalInstrVolume'] ?? 'N/A',
				];
			} else {
				// Common data structure for "Risers" and "Fallers"
				$tableRows[] = [
					'symbol' => $apiResponse['instrSysName'],
					'name' => $apiResponse['instrName'] ?? 'N/A',
					'value' => $apiResponse['price'],
					'change_euro' => $apiResponse['pricePrevClosePriceDelta'] ?? 'N/A',
					'change_percent' => $apiResponse['pricePrevClosePricePDelta'] ?? 'N/A',
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

}
