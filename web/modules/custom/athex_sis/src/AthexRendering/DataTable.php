<?php

namespace Drupal\athex_sis\AthexRendering;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * USAGE:
 *
 * 	$struct = [
 * 		[
 * 			'label' => 'Label',
 * 			'field' => 'datakey',
 * 			'pinned' => true
 * 		],
 * 		// ...
 * 	];
 *
 * 	$data = [
 * 		[
 * 			'datakey' => 'value',
 * 			// ...
 * 		],
 * 		// ...
 * 	];
 *
 * $dt = new DataTable($struct, $data);
 * return $dt->render();
 *
 */


class DataTable {

	use StringTranslationTrait;

	public $struct;
	public $data;

	public function __construct(
		Array $struct = [],
		Array $data = []
	) {
		$this->struct = $struct;
		$this->data = $data;
	}

	private function renderRowGeneric($data = null) {
		$result = [];
		foreach ($this->struct as $col) {
			$result[] = [
				'data' => (
					$data
					? (@$data[$col['field']] ?? '')
					: $this->t($col['label'])
				),
				'class' => [
					$col['pinned'] ? '' : 'mobile-hidden',
					'field--' . strtolower($col['field'])
				]
			];
		}
		return $result;
	}

	public function render() {
		return [
			'#type' => 'table',
			'#header' => $this->renderRowGeneric(),
			'#rows' => array_map([$this, 'renderRowGeneric'], $this->data)
		];
	}
}
