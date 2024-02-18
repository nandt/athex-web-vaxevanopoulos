<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;


abstract class ArticleEntryArraysIterator implements \Iterator {

	protected readonly \Iterator $pages;

    private $pagePosition = -1;
	private ?array $article = null;
	private int $count = 0;


	public function __construct(\Iterator $iterator) {
		$this->pages = $iterator;
	}

	protected function getArticle(): array {
		if ($this->article !== null)
			return $this->article;

		if (!$this->pages->valid())
			return $this->article = false;

		$page = $this->pages->current();

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
