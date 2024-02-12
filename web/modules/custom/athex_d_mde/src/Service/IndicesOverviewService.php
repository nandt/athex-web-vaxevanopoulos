<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\athex_d_mde\AthexRendering\Helpers;
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


	public function createContainer(string $seldSymbol) {
		$indicesString = $this->config->get('indices');
		$indices = explode(',', $indicesString);

		foreach ($indices as $idx => $index) {
			$indices[$idx] = $index . '.ATH';
		}

		$apiResponse = $this->api->callDelayed('Info', ['code' => "{$seldSymbol}.ATH" ]);

		$processedData = [];

		foreach ($apiResponse as $item) {
			if (is_array($item) && in_array($item['instrSysName'], $indices)) {
				$processedData[$item['instrSysName']] = Helpers::getProductRenderVars($item);
			}
		}

		return new IndicesOverviewContainer($indices, array_values($processedData)[0]);
	}

}
