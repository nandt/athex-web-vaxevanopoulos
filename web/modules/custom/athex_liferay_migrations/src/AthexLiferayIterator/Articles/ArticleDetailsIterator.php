<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;

use Drupal\athex_liferay_migrations\AthexData\LiferayArticle;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


class ArticleDetailsIterator extends ArticleEntryArraysIterator {

	private ApiDataService $api;
	private ?LiferayArticle $article = null;


	public function __construct() {
		parent::__construct();
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$data = parent::current();
		if (!$data) return null;
		if ($this->article) return $this->article;
		$this->article = $this->api->getLiferayArticle($data['classPK']);
		return $this->article;
	}

	public function next(): void {
		parent::next();
		if (!$this->valid()) return;
		$this->article = null;
	}
}
