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
		$this->config = $configFactory->get('athex_d_mde.indicessettings');
		$this->api = $api;
	}


	public function createContainer() {
    $indicesString = $this->config->get('indices') ?: 'GD.ATH,FTSE.ATH,ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH';
    $indices = explode(',', $indicesString);

    $apiResponse = $this->api->callDelayed('Info', ['code' => $indicesString, 'format' => 'json']);
    if (!is_array($apiResponse)) {
        // If response is not an array, something went wrong. Handle the error appropriately.
         \Drupal::messenger()->addError(t('Error fetching API response.'));
        return [];
    }

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

    foreach ($indices as $index) {
        if (!isset($processedData[$index])) {
            // Handle the case where the index is not found in the API response
            \Drupal::messenger()->addWarning(t('Data for index %index not found in API response.', ['%index' => $index]));

        }
    }

    return new IndicesOverviewContainer(array_keys($processedData), array_values($processedData));
}


/*
 * https://fcd-p1.inbroker.com/Info?userName=newAthexSite&IBSessionId=6CFE02B5-43B5-4BEB-A417-3EF0E1371B6F&company=InTarget&lang=GR&code=GD.ATH,FTSE.ATH,ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH&format=json
 * */





}
