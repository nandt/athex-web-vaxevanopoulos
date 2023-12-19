<?php

namespace Drupal\athex_company_pages\Controller;

use Drupal\athex_company_pages\Service\CompanyPageLayoutService;
use Drupal\athex_company_pages\Service\CompanyOverviewDataService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CompanyPagesController extends ControllerBase {

	protected $layout;
	protected $overview_data;

	public function __construct(
		CompanyPageLayoutService $layout,
		CompanyOverviewDataService $overview_data
	) {
		$this->layout = $layout;
		$this->overview_data = $overview_data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_company_pages.layout'),
			$container->get('athex_company_pages.overview_data')
		);
	}

	public function getOverview($company_id) {
		// throw new NotFoundHttpException();

		return $this->layout->render($company_id, [
			[
				'#type' => 'container',
				'title' => $this->layout->h2('Company overview'),
				'overview_table' => [
					'#type' => 'table',
					'$rows' => $this->overview_data->getOverviewRows($company_id)
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
					'title' => $this->layout->h2('Company Instruments'),
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
