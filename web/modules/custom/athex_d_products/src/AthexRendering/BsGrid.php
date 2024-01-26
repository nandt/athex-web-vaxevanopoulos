<?php

namespace Drupal\athex_d_products\AthexRendering;

class BsGrid {

	public static function renderContainer($rows) {
		return array_merge([
			'#type' => 'container',
			'#attributes' => [ 'class' => ['container', 'athex--container'] ]
		], $rows);
	}

	public static function renderRow($cols, $colspan = 3) {
		$colspan = max(12 / $colspan, 12 / count($cols));
		return array_merge(
			[
				'#type' => 'container',
				'#attributes' => [ 'class' => ['row'] ]
			],
			array_map(function($col) use ($colspan) {
				return [
					'#type' => 'container',
					'#attributes' => [ 'class' => ['col-12', "col-lg-$colspan"] ],
					$col
				];
			}, $cols)
		);
	}
}
