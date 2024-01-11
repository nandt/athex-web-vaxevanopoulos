<?php

namespace Drupal\athex_stock_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


class StockSearchController extends ControllerBase {

	// protected $layout;
	// protected $overview_data;

	public function __construct(
		// StockPageLayoutService $layout,
		// StockOverviewDataService $overview_data
	) {
		// $this->layout = $layout;
		// $this->overview_data = $overview_data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			// $container->get('athex_stock_pages.layout'),
			// $container->get('athex_stock_pages.overview_data')
		);
	}

	public function getPage() {
		// $data = $this->data->getResultData()

		// $page = \Drupal\Core\Pager\PagerManager::createPager(30, 10);

		return [
			'#theme' => 'stock_search',
			'#filters' => [],
			'#index' => [],
			'#table' => [
				'#type' => 'table',
				'#header' => [
					$this->t('Symbol'),
					$this->t('Company Name'),
					$this->t('ISIN'),
					$this->t('Market'),
					$this->t('Last'),
					$this->t('%'),
					$this->t('Date / Time')
				],
				'#rows' => []
			],
			'#pager' => [
				'#type' => 'pager'
			],
		];

		// throw new NotFoundHttpException();

		// return $this->layout->render($company_id, [
		// 	[
		// 		'#type' => 'container',
		// 		'title' => $this->layout->h2('Stock overview'),
		// 		'overview_table' => [
		// 			'#type' => 'table',
		// 			'$rows' => $this->overview_data->getOverviewRows($company_id)
		// 		],
		// 		'overview_chart' => [
		// 			//...
		// 		]
		// 	], [
		// 		'#type' => 'container',
		// 		'historic_data' => [
		// 			'#type' => 'container',
		// 			'title' => $this->layout->h2('Historic Data'),
		// 			'table' => [
		// 				'#type' => 'table',
		// 				'#header' => [
		// 					$this->t('Date'),
		// 					$this->t('High'),
		// 					$this->t('Low'),
		// 					$this->t('Close'),
		// 					$this->t('Volume')
		// 				],
		// 				'#rows' => $this->overview_data->getHistoricData($company_id)
		// 			]
		// 		],
		// 		'instruments' => [
		// 			'#type' => 'container',
		// 			'title' => $this->layout->h2('Stock Instruments'),
		// 			// 'table' => [
		// 			// 	'#type' => 'table',
		// 			// 	'#header' => [
		// 			// 		$this->t('Symbol'),
		// 			// 		$this->t('Name'),
		// 			// 		$this->t('Instrument'),
		// 			// 	]
		// 			// ]
		// 		]
		// 	]
		// ]);
	}



}
