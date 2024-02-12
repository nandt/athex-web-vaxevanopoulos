<?php

namespace Drupal\athex_d_mde\AthexRendering;


class ProductsTable
{

	public array $data;

	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	public function render()
	{
		$rows = array_map(function ($row) {
			$cols = array_map(function ($d) {
				return ['data' => $d];
			}, $row);

			if (isset($cols[0])) {
				$cols[0]['data'] = [
					'#type' => 'link',
					'#title' => $cols[0]['data'],
					'#url' => \Drupal\Core\Url::fromUri('internal:#')
				];
			}


			if (count($cols) === 5)
				$cols[1]['class'] = ['mobile-hidden'];

			return $cols;
		}, $this->data);

		return [
			'#type' => 'table',
			'#attributes' => [
				'class' => ['products-table']
			],
			'#rows' => $rows
		];
	}
}
