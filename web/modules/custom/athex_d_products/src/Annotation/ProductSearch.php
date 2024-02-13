<?php

namespace Drupal\athex_d_products\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a ProductSearch annotation object for ProductSearch plugins.
 *
 * @Annotation
 */
class ProductSearch extends Plugin {
/**
* The plugin ID.
*
* @var string
*/
public $id;

/**
* The label of the plugin.
*
* @var \Drupal\Core\Annotation\Translation
*
* @ingroup plugin_translatable
*/
public $label;
}
