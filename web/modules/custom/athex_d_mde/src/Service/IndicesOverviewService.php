<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\IndicesOverviewContainer;
use Drupal\athex_inbroker\Service\ApiDataService;

class IndicesOverviewService {

	use StringTranslationTrait;

	protected $config;
	protected $api;

	public function __construct(
		ConfigFactoryInterface $configFactory,
		ApiDataService $api
	) {
		$this->config = $configFactory->get('athex_d_mde.settings');
		$this->api = $api;
  	}

	public function createContainer() {
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
}
