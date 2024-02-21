<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Categories;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\Base\LiferayApiPagedAssetIterator;


class CategoriesIterator extends LiferayApiPagedAssetIterator {
	public function __construct() {
		parent::__construct(new VocabCategoryPagesIterator());
	}
}
