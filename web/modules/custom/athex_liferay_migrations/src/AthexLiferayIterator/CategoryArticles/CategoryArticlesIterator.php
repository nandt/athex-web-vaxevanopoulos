<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\CategoryArticles;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryIterator;


class CategoryArticlesIterator extends AssetEntryIterator {
	public function __construct() {
		parent::__construct(10108, new CategoryArticleArraysIterator());
	}

	public function current() {
		$data = parent::current();
		if (!$data) return null;
		$data->set('categoryId', $this->pages->key());
		return $data;
	}
}
