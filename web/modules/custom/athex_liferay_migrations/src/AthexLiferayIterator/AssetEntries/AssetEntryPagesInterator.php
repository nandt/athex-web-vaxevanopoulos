<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\Base\LiferayApiPagesIterator;


class AssetEntryPagesInterator extends LiferayApiPagesIterator {
	protected function pageFetcher() {
		return $this->fetchCurrentPage(
			ApiEndpoints::ASSETENTRY__GET_COMP_ENTRIES
		);
	}
}
