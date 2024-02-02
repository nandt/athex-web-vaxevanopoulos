<?php

namespace Drupal\athex_d_mde\AthexRendering;

use Drupal\Core\Url;

class BsNavLinks extends BsNav {
	public function __construct(
		array $tabs,
		string $class = 'tabs'
	) {
		foreach ($tabs as $label => $url) {
			/** @var Url $url */
			$tabs[$label] = $url->toString();
		}
		$tabs = array_flip($tabs);
		parent::__construct(
			array_values($tabs),
			$tabs[\Drupal::request()->getRequestUri()],
			$class,
			array_keys($tabs)
		);
	}
}
