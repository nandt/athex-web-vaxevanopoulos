<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryPagesInterator;


class ArticleEntryArraysIterator implements \Iterator {

	private $pages;

    private $pagePosition = -1;
	private ?array $article = null;
	private int $count = 0;


	public function __construct() {
		$this->pages = new AssetEntryPagesInterator();
	}

	protected function getArticle(): array {
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

		$this->article = $result;

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
