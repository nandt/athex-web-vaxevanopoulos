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

/*
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
			$label = $col['label'] ?? 'No Label'; // Default label if not provided

			if ($data) {
				// Use data value for rows, ensure 'field' exists in data
				$cellValue = $data[$col['field']] ?? 'N/A'; // Default to 'N/A' if data for this field is missing
			} else {
				// Use label for headers, apply translation
				$cellValue = $this->t($label);
			}

			$cell = [
				'data' => $cellValue,
				'class' => ['field--' . strtolower($col['field'])]
			];

			// Add 'mobile-hidden' class if not pinned
			if (empty($col['pinned'])) {
				$cell['class'][] = 'mobile-hidden';
			} else {
				$pins = true;
			}

			$cells[] = $cell;
		}

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
*/
class DataTable {
	use StringTranslationTrait;

	public $struct;
	public $data;

	public function __construct(array $struct = [], array $data = []) {
		$this->struct = $struct;
		$this->data = $data;
	}

	/*private function renderRowGeneric($data = null) {
		if (!is_array($data)) {
			\Drupal::logger('DataTable')->error('Data item is not an array: ' . print_r($data, TRUE));
			return []; // Return an empty array or some default structure
		}
		$cells = [];
		foreach ($this->data as $item) {
			if (is_array($item)) {
				\Drupal::logger('DataTable not array')->error('Data item is not an array: ' . print_r($item, TRUE));
			}
		}

		foreach ($this->struct as $col) {
			// Inside renderRowGeneric, before the problematic line
			\Drupal::logger('DataTable Debug')->notice('<pre>' . var_export($data, TRUE) . '</pre>');

			if ($data) {
				// Render row cells using field data
				$cellValue = $data[$col['field']] ?? 'N/A'; // Default to 'N/A' if data for this field is missing
			} else {
				// Render header cells using 'data' as label
				$cellValue = $col['data'];
			}

			$cell = [
				'data' => $cellValue,
				'class' => ['field--' . strtolower($col['field'])]
			];

			// Add 'mobile-hidden' class if not pinned
			if (empty($col['pinned'])) {
				$cell['class'][] = 'mobile-hidden';
			}

			$cells[] = $cell;
		}

		return $cells;
	}
*/
	private function renderRowGeneric($data = null) {
		$cells = [];
		\Drupal::logger('data results from dt table')->notice('<pre>' . print_r($data, TRUE) . '</pre>');
		foreach ($this->struct as $col) {
			// Ensure $col is an array and has 'field' and 'data' keys
			if (!is_array($col) || !isset($col['field']) || !isset($col['data'])) {
				\Drupal::logger('DataTable')->error('Invalid column structure: @col', ['@col' => print_r($col, TRUE)]);
				continue; // Skip this column
			}

			if ($data) {
				// Render row cells using field data
				$cellValue = $data[$col['field']] ?? 'N/A'; // Default to 'N/A' if data for this field is missing
			} else {
				// Render header cells using 'data' as label
				$cellValue = $col['data'];
			}

			$cell = [
				'data' => $cellValue,
				'class' => ['field--' . strtolower($col['field'])]
			];

			// Add 'mobile-hidden' class if not pinned
			if (empty($col['pinned'])) {
				$cell['class'][] = 'mobile-hidden';
			}

			$cells[] = $cell;
		}

		return $cells;
	}

	public function render() {
		// Debugging: Check the type of each data item before passing to renderRowGeneric
		foreach ($this->data as $dataItem) {
			if (is_array($dataItem)) {
				// Log the issue or handle it as needed
				\Drupal::logger('DataTable file ')->error('Data item is not an array: ' . print_r($dataItem, TRUE));
			}
		}

		return [
			'#type' => 'table',
			'#header' => $this->renderRowGeneric(),
			'#rows' => array_map([$this, 'renderRowGeneric'], $this->data)
		];
	}

}
