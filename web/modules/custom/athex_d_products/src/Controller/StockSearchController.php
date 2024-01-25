<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\athex_d_products\AthexRendering\ProductSearch;
use Drupal\athex_d_products\Service\StockDataService;


class StockSearchController extends ControllerBase {

	protected ProductSearch $search;
	protected StockDataService $data;

	private function getFilterRA($name) {
		return [
			'#type' => 'details',
			'#title' => $this->t($name)
		];
	}

	public function __construct(
		StockDataService $data
	) {
		$this->data = $data;
		$this->search = new ProductSearch(
			'Stock Search', [
				$this->getFilterRA('Market'),
				$this->getFilterRA('Industry'),
				$this->getFilterRA('Closing Price'),
				$this->getFilterRA('Date Range')
			]
		);
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data')
		);
	}

	public function render() {
		$data = $this->data->search(
			[],
			$this->search->getResultsOffset(),
			$this->search->getResultsLimit()
		);

		foreach ($data as &$row) {
			$row['symbol'] = [
				'#type' => 'link',
				'#title' => $row['symbol'],
				'#url' => \Drupal\Core\Url::fromRoute(
					'athex_d_products.stock_profile',
					[
						'product_id' => $row['symbol']
					]
				)
			];
		}

		return $this->search->render(
			new DataTable(
				[
					[ 'field' => 'symbol',		'label' => 'Symbol',		'pinned' => true ],
					[ 'field' => 'company',		'label' => 'Company Name'	 ], // WARN: has css styles on .field--company
					[ 'field' => 'isin',		'label' => 'ISIN'			 ],
					[ 'field' => 'market',		'label' => 'Market'			 ],
					[ 'field' => 'last',		'label' => 'Last'			 ],
					[ 'field' => 'percent',		'label' => '%',				'pinned' => true ],
					[ 'field' => 'date_time',	'label' => 'Date / Time'	 ]
				],
				$data
			)
		);
	}
}
