<?php

namespace Drupal\athex_d_mde\AthexRendering;

class Helpers {
	public static function renderDelta($val, $suffix = '', $prefix = '') {
		$class = null;

		if ($val > 0) {
			$prefix .= '+';
			$class = 'pos-change';
		}
		else if ($val < 0) {
			$class = 'neg-change';
		}

		return [
			'#type' => 'html_tag',
			'#tag' => 'span',
			'#attributes' => $class ? [ 'class' => [$class] ] : [],
			'#value' => $prefix . $val . $suffix
		];
	}
}
