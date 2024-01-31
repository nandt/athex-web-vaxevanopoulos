<?php

namespace Drupal\athex_d_mde\AthexRendering;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * USAGE:
 *
 * 	$struct = [
 * 		[
 * 			'label' => 'EN Label',
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
		$pins = false;
		$cells = [];

		foreach ($this->struct as $col) {
			$cell = [
				'data' => (
					$data
					? (@$data[$col['field']] ?? '')
					: $this->t($col['label'])
				),
				'class' => [
					'field--' . strtolower($col['field'])
				]
			];

			if (!@$col['pinned'])
				$cell['class'][] = 'mobile-hidden';
			else
				$pins = true;

			$cells[] = $cell;
		}

		if (!$pins)
			array_pop($cells[0]['class']);

		return $cells;
	}

	public function render() {
		return [
			'#type' => 'table',
			'#header' => $this->renderRowGeneric(),
			'#rows' => array_map([$this, 'renderRowGeneric'], $this->data)
		];
	}
}
