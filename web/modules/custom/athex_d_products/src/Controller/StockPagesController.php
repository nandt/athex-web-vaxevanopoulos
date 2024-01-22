<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_products\Service\StockPageLayoutService;
use Drupal\athex_d_products\Service\StockOverviewDataService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;


class StockPagesController extends ControllerBase {

	protected $layout;
	protected $overview_data;

	public function __construct(
		StockPageLayoutService $layout,
		StockOverviewDataService $overview_data
	) {
		$this->layout = $layout;
		$this->overview_data = $overview_data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.layout'),
			$container->get('athex_d_products.overview_data')
		);
	}

	public function getOverview($company_id) {
		// throw new NotFoundHttpException();

		return $this->layout->render($company_id, [
			[
				'#type' => 'container',
				'title' => $this->layout->h2('Stock overview'),
				'overview_table' => [
					'#type' => 'table',
					'#rows' => $this->overview_data->getOverviewRows($company_id)
				],
				'overview_chart' => [
					//...
				]
			], [
				'#type' => 'container',
				'historic_data' => [
					'#type' => 'container',
					'title' => $this->layout->h2('Historic Data'),
					'table' => [
						'#type' => 'table',
						'#header' => [
							$this->t('Date'),
							$this->t('High'),
							$this->t('Low'),
							$this->t('Close'),
							$this->t('Volume')
						],
						'#rows' => $this->overview_data->getHistoricData($company_id)
					]
				],
				'instruments' => [
					'#type' => 'container',
					'title' => $this->layout->h2('Stock Instruments'),
					// 'table' => [
					// 	'#type' => 'table',
					// 	'#header' => [
					// 		$this->t('Symbol'),
					// 		$this->t('Name'),
					// 		$this->t('Instrument'),
					// 	]
					// ]
				]
			]
		]);
	}



}
