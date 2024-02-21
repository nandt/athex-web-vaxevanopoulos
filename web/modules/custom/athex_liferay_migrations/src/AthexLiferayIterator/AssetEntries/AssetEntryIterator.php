<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries;

use Drupal\athex_liferay_migrations\AthexData\LiferayAssetEntry;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryArraysIterator;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryPagesInterator;


class AssetEntryIterator extends AssetEntryArraysIterator {

	private ?LiferayAssetEntry $asset = null;

	public function __construct(int $classNameId, \Iterator $iterator = null) {
		parent::__construct($iterator ?: new AssetEntryPagesInterator(), $classNameId);
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$data = parent::current();
		if (!$data) return null;
		if ($this->asset) return $this->asset;
		$this->asset = new LiferayAssetEntry($data);
		return $this->asset;
	}

	public function next(): void {
		parent::next();
		if (!$this->valid()) return;
		$this->asset = null;
	}
}
