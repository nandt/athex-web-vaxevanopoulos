<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;


class ArticleDataIterator implements \Iterator {

	private \Iterator $articles;
	private bool $defaultLang;

    private int $langPosition = -1;
	private ?array $articleData = null;
	private int $count = 0;

	public function __construct(
		\Iterator $iterator,
		bool $translationsMode
	) {
		$this->articles = $iterator;
		$this->defaultLang = !$translationsMode;
	}

	private function getArticleData() {
		if ($this->articleData !== null)
			return $this->articleData;

		$article = $this->articles->current();
		if (!$article)
			return $this->articleData = false;

		$idx = ++$this->langPosition;

		if (!$this->defaultLang && $idx === 0)
			$idx = ++$this->langPosition;

		$result = $article->getData($idx);

		if ($result && $this->defaultLang && $idx > 0)
			$result = null;

		if ($result === null) {
			$this->langPosition = -1;
			$this->articles->next();
			return $this->getArticleData();
		}

		return $this->articleData = $result;
	}

    #[\ReturnTypeWillChange]
    public function current() {
		$data = $this->getArticleData();
        return $data ?: null;
    }

    public function valid(): bool {
		return !!$this->current();
    }

    public function next(): void {
		if (!$this->valid()) return;
		$this->articleData = null;
		++$this->count;
    }

	public function key() {
		return $this->count;
	}

	public function rewind(): void {
		$this->langPosition = -1;
		$this->articleData = null;
		$this->count = 0;
		$this->articles->rewind();
	}
}
