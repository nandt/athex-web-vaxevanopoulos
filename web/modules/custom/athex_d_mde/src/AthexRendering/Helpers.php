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

		if ($suffix === '%') {
			$val = number_format($val, 1);
		}

		return [
			'#type' => 'html_tag',
			'#tag' => 'span',
			'#attributes' => $class ? [ 'class' => [$class] ] : [],
			'#value' => $prefix . $val . $suffix
		];
	}

	public static function hashtagKeys($array) {
		return array_combine(array_map(function($key) {
			return '#' . $key;
		}, array_keys($array)), array_values($array));

	}

	public static function getProductRenderVars($info) {
		return [
			'symbol'
				=> $info['instrSysName'],
			'value'
				=> $info['closePrice'] ?: $info['price'] ?: $info['prevClosePrice'],
			'since_open_value'
				=> Helpers::renderDelta($info['pricePrevPriceDelta']),
			'since_open_percentage'
				=> Helpers::renderDelta($info['pricePrevPricePDelta'], '%'),
			'since_close_value'
				=> Helpers::renderDelta($info['pricePrevClosePriceDelta']),
			'since_close_percentage'
				=> Helpers::renderDelta($info['pricePrevClosePricePDelta'], '%'),
			'change'
				=> Helpers::renderDelta($info['pricePrevClosePricePDelta'], '%'),
			'change_value'
				=> Helpers::renderDelta($info['pricePrevClosePriceDelta'])
		];
	}
}
