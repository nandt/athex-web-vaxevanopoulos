<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use Drupal\athex_liferay_migrations\Service\ApiDataService;


class LiferayVocabulariesIterator implements \Iterator {

	private ApiDataService $api;
	private LiferayVocabulariesIterator $vocabs;
	private array $category;

	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
		$this->vocabs = new LiferayVocabulariesIterator();
	}

	private function fetchCategories($parent) {
		// return $this->api->call(
		// 	ApiEndpoints::JOURNALARTICLE__GET_LATEST,
		// 	[
		// 		'resourcePrimKey' => $parent
		// 	]
		// );
	}

	private function getCategory(): array {
		if ($this->category !== null)
			return $this->category;

		$vocab = $this->vocabs->current();
		if (!$vocab)
			return $this->category = false;

		$result = null;
		// do {
		// 	$idx = ++$this->pagePosition;
		// }

	}
}
