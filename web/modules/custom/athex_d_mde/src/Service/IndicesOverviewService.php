<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\Helpers;
use Drupal\athex_d_mde\AthexRendering\IndicesOverviewContainer;
use Drupal\athex_inbroker\Service\ApiDataService;

class IndicesOverviewService {

	use StringTranslationTrait;

	protected $config;
	protected $api;

	public function __construct(
		ConfigFactoryInterface $configFactory,
		ApiDataService         $api
	) {
		$this->config = $configFactory->get('athex_d_mde.settings');
		$this->api = $api;
  	}


	public function createContainer() {
		$indices = ['GD.ATH', 'FTSE.ATH', 'ETE.ATH', 'ALPHA.ATH', 'TPEIR.ATH', 'EXAE.ATH'];
		$indicesString = join(',', $indices);

		// Fetch data from the API
		$apiResponse = $this->api->callDelayed('Info', ['code' => $indicesString, 'format' => 'json']);
		//var_dump($apiResponse); // This will print the structure of $items
		// Initialize an array to store processed data
		$processedData = [];

		// Iterate through each item in the API response
		foreach ($apiResponse as $item) {
			// Check if the current item's instrSysName is in the $indices array
			if (in_array($item['instrSysName'], $indices)) {
				$processedData[$item['instrSysName']] = [
					'symbol' => $item['instrSysName'],
					'value' => $item['price'],
					'since_open_value'
						=> Helpers::renderDelta($item['pricePrevPriceDelta']),
					'since_open_percentage'
						=> Helpers::renderDelta($item['pricePrevPricePDelta'], '%'),
					'since_close_value'
						=> Helpers::renderDelta($item['pricePrevClosePriceDelta']),
					'since_close_percentage'
						=> Helpers::renderDelta($item['pricePrevClosePricePDelta'], '%')
				];
			}
		}
/*
 * https://fcd-p1.inbroker.com/Info?userName=newAthexSite&IBSessionId=6CFE02B5-43B5-4BEB-A417-3EF0E1371B6F&company=InTarget&lang=GR&code=GD.ATH,FTSE.ATH,ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH&format=json
 * */
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
