<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


class VocabCategoryPagesIterator implements \Iterator {

	private ApiDataService $api;
	private LiferayVocabulariesIterator $vocabs;
	private array $categories;
	private int $vocabIdx = -1;

	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
		$this->vocabs = new LiferayVocabulariesIterator();
	}

	private function fetchVocabCategories() {
		$vocab = $this->vocabs[$this->vocabIdx];
		if (!$vocab) return [];
		return $this->api->call(
			ApiEndpoints::ASSETCATEG__GET_VOCAB_CATEGS,
			[
				'vocabularyId' => $vocab['vocabularyId'],
				// 'start' => ($this->pageIdx * ALMAI_PAGESIZE) + 1,
				// 'end' => (($this->pageIdx + 1) * ALMAI_PAGESIZE) + 1
			]
		);
	}

	private function getCategories(): array {
		if ($this->categories !== null)
			return $this->categories;

		++$this->vocabIdx;
		$vocab = $this->fetchVocabCategories();

		if (empty($vocab))
			return $this->categories = false;

		$this->categories = $vocab;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$cats = $this->getCategories();
		return $cats ?: null;
	}

	public function valid(): bool {
		return !!$this->current();
	}

	public function next(): void {
		if (!$this->valid()) return;
		$this->categories = null;
	}

	public function key() {
		return $this->vocabIdx + 1;
	}

	public function rewind(): void {
		$this->vocabIdx = -1;
		$this->categories = null;
	}
}
