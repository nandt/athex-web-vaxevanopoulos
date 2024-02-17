<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;

use Drupal\athex_liferay_migrations\AthexData\LiferayArticle;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryPagesInterator;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


class ArticlesIterator implements \Iterator {

	protected ApiDataService $api;

    private $pagePosition = -1;
	private ?LiferayArticle $article = null;
	private int $count = 0;
	private $pages;


	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
		$this->pages = new AssetEntryPagesInterator();
	}

	private function getArticle(): LiferayArticle {
		if ($this->article !== null)
			return $this->article;

		$page = $this->pages->current();
		if (empty($page))
			return $this->article = false;

		$result = null;
		do {
			$idx = ++$this->pagePosition;
			$result = @$page[$idx];
		}
		while (
			$result && $result['classNameId'] !== 10108
		);

		if (!$result) {
			$this->pagePosition = -1;
			$this->pages->next();
			return $this->getArticle();
		}

		$this->article = $this->api->getLiferayArticle($result['classPK']);

		return $this->article;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$data = $this->getArticle();
		return $data ?: null;
	}

	public function valid(): bool {
		return !!$this->current();
	}

	public function next(): void {
		if (!$this->valid()) return;
		$this->article = null;
		++$this->count;
	}

	public function key() {
		return $this->count;
	}

	public function rewind(): void {
		$this->pagePosition = -1;
		$this->article = null;
		$this->count = 0;
		$this->pages->rewind();
	}
}
