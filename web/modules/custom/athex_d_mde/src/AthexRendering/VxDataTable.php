<?php

namespace Drupal\athex_d_mde\AthexRendering;


use Drupal\Core\StringTranslation\StringTranslationTrait;

class VxDataTable {
	use StringTranslationTrait;

	protected $data;
	protected $headers;

	public function __construct(array $data, array $headers) {
		$this->data = $data;
		$this->headers = $headers;
	}

	public function render() {
		$header = [];
		foreach ($this->headers as $header_item) {
			// Convert header data to string if it's an object
			$headerData = is_object($header_item['data']) ? (method_exists($header_item['data'], '__toString') ? $header_item['data']->__toString() : 'Object') : $header_item['data'];
			$header[] = ['data' => $this->t($headerData)];
		}

		$rows = [];
		foreach ($this->data as $row_data) {
			$row = [];
			foreach ($this->headers as $header_item) {
				$field = $header_item['field'];
				$rowData = isset($row_data[$field]) ? (is_object($row_data[$field]) && method_exists($row_data[$field], '__toString') ? $row_data[$field]->__toString() : $row_data[$field]) : 'N/A';
				$row[] = ['data' => $rowData];
			}
			$rows[] = $row;
		}

		return [
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $rows,
			'#attributes' => ['class' => ['athex-data-table']],
		];
	}

}
