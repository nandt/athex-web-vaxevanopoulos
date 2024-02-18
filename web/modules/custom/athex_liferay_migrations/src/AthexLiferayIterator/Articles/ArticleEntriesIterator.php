<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles;

use Drupal\athex_liferay_migrations\AthexData\LiferayAssetEntry;


class ArticleEntriesIterator extends ArticleEntryArraysIterator {

	private ?LiferayAssetEntry $article = null;


	#[\ReturnTypeWillChange]
	public function current() {
		$data = parent::current();
		if (!$data) return null;
		if ($this->article) return $this->article;
		$this->article = new LiferayAssetEntry($data);
		return $this->article;
	}

	public function next(): void {
		parent::next();
		if (!$this->valid()) return;
		$this->article = null;
	}
}
