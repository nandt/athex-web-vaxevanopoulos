<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\CategoryArticles;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles\ArticleEntriesIterator;

class CategoryArticlesIterator extends ArticleEntriesIterator {
	public function __construct() {
		parent::__construct(new CategoryArticleArraysIterator());
	}

	public function current() {
		$data = parent::current();
		if (!$data) return null;
		$data->set('categoryId', $this->pages->key());
		return $data;
	}
}
