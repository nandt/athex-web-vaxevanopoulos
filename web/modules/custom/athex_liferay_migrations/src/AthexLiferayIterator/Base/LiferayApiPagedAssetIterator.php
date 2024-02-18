<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Base;

abstract class LiferayApiPagedAssetIterator implements \Iterator {

	protected $pageIdx = -1;
	private int $count = 0;
	private $asset = null;
	private $iterator;

	public function __construct(LiferayApiPagesIterator $iterator) {
		$this->iterator = $iterator;
	}

	private function getAsset() {
		if ($this->asset !== null)
			return $this->asset;

		if (!$this->iterator->valid())
			return $this->asset = false;

		$page = $this->iterator->current();

		$asset = @$page[++$this->pageIdx];

		if (!$asset) {
			$this->pageIdx = -1;
			$this->iterator->next();
			return $this->getAsset();
		}

		return $this->asset = $asset;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$asset = $this->getAsset();
		return $asset ?: null;
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
		$this->pageIdx = -1;
		$this->count = 0;
		$this->asset = null;
	}
}
