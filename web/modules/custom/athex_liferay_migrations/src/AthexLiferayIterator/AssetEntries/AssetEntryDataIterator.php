<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries;


class AssetEntryDataIterator implements \Iterator {

	private \Iterator $assets;
	private bool $defaultLang;

    private int $langPosition = -1;
	private $assetData = null;
	private int $count = 0;

	public function __construct(
		\Iterator $iterator,
		bool $translationsMode
	) {
		$this->assets = $iterator;
		$this->defaultLang = !$translationsMode;
	}

	private function getAssetData() {
		if ($this->assetData !== null)
			return $this->assetData;

		$asset = $this->assets->current();
		if (!$asset)
			return $this->assetData = false;

		$idx = ++$this->langPosition;

		if (!$this->defaultLang && $idx === 0)
			$idx = ++$this->langPosition;

		$result = $asset->getData($idx);

		if ($result && $this->defaultLang && $idx > 0)
			$result = null;

		if ($result === null) {
			$this->langPosition = -1;
			$this->assets->next();
			return $this->getAssetData();
		}

		return $this->assetData = $result;
	}

    #[\ReturnTypeWillChange]
    public function current() {
		$data = $this->getAssetData();
        return $data ?: null;
    }

    public function valid(): bool {
		return !!$this->current();
    }

    public function next(): void {
		if (!$this->valid()) return;
		$this->assetData = null;
		++$this->count;
    }

	public function key() {
		return $this->count;
	}

	public function rewind(): void {
		$this->langPosition = -1;
		$this->assetData = null;
		$this->count = 0;
		$this->assets->rewind();
	}
}
