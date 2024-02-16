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
		$cells = [];

		foreach ($this->struct as $col) {
			// Debug logging
			// Assuming $col is an array and you want to log only the first 5 elements


			if ($data) {
			//\Drupal::logger('DataTable Debug data')->notice('<pre>' . print_r($data, TRUE) . '</pre>');

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
	}*/

	/*private function renderRowGeneric($data = null) {
		$cells = [];
		foreach ($this->struct as $col) {
			// Debug logging
			// Assuming $col is an array and you want to log only the first 5 elements
			if ($data) {
				// Render row cells using field data
				// Apply stringifyValue() to the data cell value
				$cellValue = $this->stringifyValue($data[$col['field']] ?? 'N/A');
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
	}*/
	/*private function renderRowGeneric($data = null) {
		$cells = [];

		foreach ($this->struct as $col) {
			if ($data) {
				// Check if $data[$col['field']] is an array and handle it accordingly
				if (is_array($data[$col['field']])) {
					// Handle array case
					$cellValue = implode(', ', $data[$col['field']]); // Example way to handle array
				} else {
					// If it's not an array, use it as is
					$cellValue = $data[$col['field']] ?? 'N/A'; // Default to 'N/A' if data for this field is missing
				}
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
	}*/
	private function renderRowGeneric($data = null) {
		$cells = [];

		foreach ($this->struct as $col) {
			if ($data) {
				\Drupal::logger('DataTable Debug DATA')->notice('Data type: ' . gettype($data) . ', Value: ' . print_r($data, TRUE));
				\Drupal::logger('DataTable Debug FIELD')->notice('Field: ' . $col['field']);

				if (isset($data[$col['field']])) {
					$cellValue = $data[$col['field']];
				} else {
					// Log missing key
					\Drupal::logger('DataTable')->warning('Missing key: ' . $col['field']);
					$cellValue = 'N/A'; // Fallback value
				}
			} else {
				$cellValue = $col['data']; // Use column data as header label
			}

			$cell = [
				'data' => $cellValue,
				'class' => ['field--' . strtolower($col['field'])]
			];

			if (empty($col['pinned'])) {
				$cell['class'][] = 'mobile-hidden';
			}

			$cells[] = $cell;
		}

		return $cells;
	}



	private function stringifyValue($value) {
		if (is_array($value)) {
			return implode(', ', $value);
		}
		return $value;
	}


	public function render() {
		// Validate data structure before rendering
		foreach ($this->data as $item) {
			if (!is_array($item) || array_diff_key(array_flip(array_column($this->struct, 'field')), $item)) {
				\Drupal::logger('DataTable Render')->warning('Invalid data structure for DataTable rendering: ' . print_r($item, TRUE));
				continue; // Skip invalid data items
			}
		}

		return [
			'#type' => 'table',
			'#header' => $this->renderRowGeneric(),
			'#rows' => array_map([$this, 'renderRowGeneric'], $this->data)
		];
	}
}
