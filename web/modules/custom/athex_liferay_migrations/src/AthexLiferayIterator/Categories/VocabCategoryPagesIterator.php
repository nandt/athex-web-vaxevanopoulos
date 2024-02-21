<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Categories;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\Base\LiferayApiPagesIterator;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\Vocabularies\VocabulariesIterator;

class VocabCategoryPagesIterator extends LiferayApiPagesIterator {

	private $vocabs;

	public function __construct() {
		parent::__construct();
		$this->vocabs = new VocabulariesIterator();
	}

	protected function pageFetcher() {
		$vocab = $this->vocabs->current();
		if (!$vocab)
			return [];

		$results = $this->fetchCurrentPage(
			ApiEndpoints::ASSETCATEG__GET_VOCAB_CATEGS, [
				'vocabularyId' => $vocab['vocabularyId'],
				'+obc' => 'com.liferay.portlet.journal.util.comparator.ArticleCreateDateComparator'
			]
		);

		if (!empty($results))
			return $results;

		$this->vocabs->next();
		$this->pageIdx = 0;
		return $this->pageFetcher();
	}
}
