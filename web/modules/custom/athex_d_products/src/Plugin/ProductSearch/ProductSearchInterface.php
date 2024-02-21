<?php

namespace Drupal\athex_d_products\Plugin\ProductSearch;

interface ProductSearchInterface {
	public function getQuery(array $filters, int $offset, int $limit);
	public function getFilters();
	public function getHeaders();
}
