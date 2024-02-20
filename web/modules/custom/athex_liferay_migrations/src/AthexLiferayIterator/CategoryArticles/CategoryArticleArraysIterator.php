<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\CategoryArticles;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\Categories\CategoriesIterator;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


class CategoryArticleArraysIterator extends CategoriesIterator {

	private ApiDataService $api;
	private ?array $articleList = null;

	public function __construct() {
		parent::__construct();
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
	}

	private function fetchArticleList(int $categoryId): array {
		return $this->api->call(
			ApiEndpoints::ASSETENTRY__GET_ENTRIES,
			[
				'+entryQuery' => 'com.liferay.portlet.asset.service.persistence.AssetEntryQuery',
				'entryQuery.allCategoryIds' => $categoryId
			]
		);
	}

	public function current() {
		$data = parent::current();
		if (!$data) return null;
		if ($this->articleList) return $this->articleList;
		$this->articleList = $this->fetchArticleList($data['categoryId']);
		return $this->articleList;
	}

	public function next(): void {
		parent::next();
		if (!$this->valid()) return;
		$this->articleList = null;
	}

	public function key() {
		$data = parent::current();
		if (!$data) return null;
		return $data['categoryId'];
	}
}
