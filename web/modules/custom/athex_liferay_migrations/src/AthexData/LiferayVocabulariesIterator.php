<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use ArrayIterator;
use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


class LiferayVocabulariesIterator extends ArrayIterator {

	protected ApiDataService $api;

	private function fetchAll() {
		return $this->api->call(
			ApiEndpoints::ASSETVOCAB__GET_COMPANY_VOCAB
		);
	}

	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
		$result = $this->fetchAll();
		parent::__construct($result);
	}
}
