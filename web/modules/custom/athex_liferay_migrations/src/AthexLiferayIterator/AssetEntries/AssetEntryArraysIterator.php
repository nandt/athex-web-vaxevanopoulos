<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries;


abstract class AssetEntryArraysIterator implements \Iterator {

	protected readonly \Iterator $pages;
	protected readonly int $classNameId;

    private $pagePosition = -1;
	private $asset = null;
	private int $count = 0;


	public function __construct(\Iterator $iterator, int $classNameId) {
		$this->pages = $iterator;
		$this->classNameId = $classNameId;
	}

	protected function getAsset() {
		if ($this->asset !== null)
			return $this->asset;

		if (!$this->pages->valid())
			return $this->asset = false;

		$page = $this->pages->current();

		$result = null;
		do {
			$idx = ++$this->pagePosition;
			$result = @$page[$idx];
		}
		while (
			$result && $result['classNameId'] !== $this->classNameId
		);

		if (!$result) {
			$this->pagePosition = -1;
			$this->pages->next();
			return $this->getAsset();
		}

		$this->asset = $result;

		return $this->asset;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$data = $this->getAsset();
		return $data ?: null;
	}

	public function valid(): bool {
		return !!$this->current();
	}

	public function next(): void {
		if (!$this->valid()) return;
		$this->asset = null;
		++$this->count;
	}

	public function key() {
		return $this->count;
	}

	public function rewind(): void {
		$this->pagePosition = -1;
		$this->asset = null;
		$this->count = 0;
		$this->pages->rewind();
	}
}
