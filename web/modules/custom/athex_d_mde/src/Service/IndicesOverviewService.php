<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\IndicesOverviewContainer;
use Drupal\athex_inbroker\Service\ApiDataService;

class IndicesOverviewService
{

	use StringTranslationTrait;

	protected $config;
	protected $api;

	public function __construct(
		ConfigFactoryInterface $configFactory,
		ApiDataService         $api
	)
	{
		$this->config = $configFactory->get('athex_d_mde.settings');
		$this->api = $api;
	}

	/*public function createContainer() {
		return new IndicesOverviewContainer(
			['GD', 'FTSE', 'FTSEM', 'FTSED', 'ATHEX ESG', 'FTSEMSFW'],
			[
				'symbol' => 'GD',
				'value' => 1167.93,
				'since_open_value' => 17.89,
				'since_open_percentage' => 1.46,
				'since_close_value' => 204.11,
				'since_close_percentage' => 6.05
			]
		);
	}
}*/
	/*$apiResponse = $this->api->callDelayed('Info', [
				'userName' => 'newAthexSite',
				'IBSessionId' => '6CFE02B5-43B5-4BEB-A417-3EF0E1371B6F', // Replace with actual session ID
				'company' => 'InTarget',
				'lang' => 'GR',
				'code' => 'GD.ATH,FTSE.ATH,ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH',
				'format' => 'json'
			]);*/


	/*public function createContainer()
	{
		// Example indices
		//$codes = join(',', $codes);//are those the parameters
		//$items = $this->api->callDelayed('Info', ['code' => $codes]);
		$indices = ['GD.ATH', 'FTSE.ATH', 'ETE.ATH', 'ALPHA.ATH','TPEIR.ATH','EXAE.ATH'];
		$indicesString = join(',', $indices);

		// Fetch data from the API
		//$apiResponse = $this->api->call('YourApiEndpoint', ['parameter' => 'value']); // Adjust with correct endpoint and parameters
		$apiResponse = $this->api->callDelayed('Info', ['code' => $indicesString,
			'format' => 'json']);

		var_dump($apiResponse); // This will print the structure of $items

		$processedData = [];
		foreach ($indices as $index) {
			// Assuming the API response contains data indexed by the symbol name
			if (isset($apiResponse[$index])) {
				$item = $apiResponse[$index];
				$processedData[] = [
					'symbol' => $index,
					'value' => $item['price'], // Adjust field name as per API response
					'since_open_value' => $item['pricePrevPriceDelta'], // Adjust field name
					'since_open_percentage' => $item['pricePrevPricePDelta'], // Adjust field name
					'since_close_value' => $item['pricePrevClosePriceDelta'], // Adjust field name
					'since_close_percentage' => $item['pricePrevClosePricePDelta'] // Adjust field name
				];
			}
		}

		return new IndicesOverviewContainer($indices, $processedData);

	}
*/
	public function createContainer()
	{
		$indices = ['GD.ATH', 'FTSE.ATH', 'ETE.ATH', 'ALPHA.ATH', 'TPEIR.ATH', 'EXAE.ATH'];
		$indicesString = join(',', $indices);

		// Fetch data from the API
		$apiResponse = $this->api->callDelayed('Info', ['code' => $indicesString, 'format' => 'json']);

		// Initialize an array to store processed data
		$processedData = [];

		// Iterate through each item in the API response
		foreach ($apiResponse as $item) {
			// Check if the current item's instrSysName is in the $indices array
			if (in_array($item['instrSysName'], $indices)) {
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

		// For debugging: print the processed data for each index
		foreach ($indices as $index) {
			if (isset($processedData[$index])) {
				//var_dump($processedData[$index]);
			} else {
				echo "Data for index {$index} not found in API response.\n";
			}
		}

		// Create and return the container with the processed data
		return new IndicesOverviewContainer(array_keys($processedData), array_values($processedData));
	}








}
