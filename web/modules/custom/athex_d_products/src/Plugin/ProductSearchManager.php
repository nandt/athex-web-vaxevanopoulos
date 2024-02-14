<?php

namespace Drupal\athex_d_products\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

class ProductSearchManager extends DefaultPluginManager {
	public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler)
	{
		parent::__construct('Plugin/ProductSearch', $namespaces, $module_handler, 'Drupal\athex_d_products\Plugin\ProductSearch\ProductSearchInterface', 'Drupal\athex_d_products\Annotation\ProductSearch');
		$this->alterInfo('product_search_info');
		$this->setCacheBackend($cache_backend, 'product_search_plugins');

	}}

