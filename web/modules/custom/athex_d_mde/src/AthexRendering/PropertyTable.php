<?php

namespace Drupal\athex_d_mde\AthexRendering;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * USAGE:
 *
 * 	$struct = [
 * 		'datakey' => 'EN Label'
 * 		// ...
 * 	];
 *
 * 	$data = [
 * 		'datakey' => ['value']
 * 		// ...
 * 	];
 *
 * $dt = new PropertyTable($struct, $data);
 * return $dt->render();
 *
 */

class PropertyTable {

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

	public function render($cols = 1) {
		$markup = '<table><tbody>';

		foreach ($this->struct as $key => $label) {
			$markup .= "<tr>";
			$markup .= "<th>{$this->t($label)}</th>";

			$isArr = is_array($this->data[$key]);
			for ($col = 0; $col < $cols; $col++) {
				$markup .= "<td>";
				$markup .= (
					$isArr
					? $this->data[$key][$col]
					: ($col ? "" : $this->data[$key])
				);
				$markup .= "</td>";
			}
			$markup .= "</tr>";
		}

		$markup .= '</tbody></table>';

		return [ '#markup' => $markup ];
	}
}
