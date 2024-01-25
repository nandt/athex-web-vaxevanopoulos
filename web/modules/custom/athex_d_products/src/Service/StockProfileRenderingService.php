<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\athex_d_mde\AthexRendering\PropertyTable;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\athex_d_products\AthexRendering\BsGrid;
use Drupal\athex_d_products\AthexRendering\ProductPage;
use Drupal\athex_d_products\Service\StockDataService;


class StockProfileRenderingService extends ControllerBase {

	protected $data;

	public function __construct(
		StockDataService $data
	) {
		$this->data = $data;
	}

	private function dummyReplicate($data) {
		$result = [];
		for ($i = 0; $i < 6; $i++) {
			$result[] = $data;
		}
		return $result;
	}

	private function renderSummaryTable() {
		return [
			'#type' => 'container',
			(new PropertyTable(
				[
					'SummaryTableKey' => 'SummaryTableENLabel'
				],
				[
					'SummaryTableKey' => ['SummaryTableValue']
				]
			))->render(),
			[
				'#type' => 'container',
				'#attributes' => [ 'class' => ['athex-table-footer'] ],
				[
					'#type' => 'html_tag',
					'#tag' => 'span',
					'#value' => $this->t('15 minutes delayed data.')
				]
			]
		];
	}

	private function renderSummaryChart() {
		return [ '#markup' => 'SummaryChart' ];
	}

	private function renderStockOverviewTable() {
		return (new PropertyTable(
			[
				'StockOverviewTableKey' => 'StockOverviewTableENLabel'
			],
			[
				'StockOverviewTableKey' => ['StockOverviewTableValue']
			]
		))->render();
	}

	private function renderTradingInfoTable() {
		return (new PropertyTable(
			[
				'TradingInfoTableKey' => 'TradingInfoTableENLabel'
			],
			[
				'TradingInfoTableKey' => ['TradingInfoTableValue']
			]
		))->render();
	}

	private function renderKeyStatisticsTable() {
		return (new PropertyTable(
			[
				'KeyStatisticsTableKey' => 'KeyStatisticsTableENLabel'
			],
			[
				'KeyStatisticsTableKey' => ['KeyStatisticsTableValue']
			]
		))->render();
	}

	private function renderIndexWeightTable() {
		return [
			'#type' => 'container',
			(new DataTable(
				[
					[ 'field' => 'index',	'label' => 'Index' ],
					[ 'field' => 'weight',	'label' => '%' ]
				],
				$this->dummyReplicate([
					'index' => 'GD',
					'weight' => '7'
				])
			))->render(),
			[
				'#type' => 'container',
				'#attributes' => [ 'class' => ['athex-table-footer'] ],
				[
					'#type' => 'html_tag',
					'#tag' => 'span',
					'#value' => $this->t('15 minutes delayed data.')
				]
			]
		];
	}

	private function renderHistoricDataTable() {
		return [
			'#type' => 'container',
			(new DataTable(
				[
					[ 'field' => 'date',	'label' => 'Date' ],
					[ 'field' => 'close',	'label' => 'Close' ],
					[ 'field' => 'open',	'label' => 'Open' ],
					[ 'field' => 'volume',	'label' => 'Volume' ],
					[ 'field' => 'value',	'label' => 'Value' ],
				],
				$this->dummyReplicate([
					'date' => '2019-01-01',
					'close' => '1.00',
					'open' => '1.00',
					'volume' => '100',
					'value' => '100.00'
				])
			))->render(),
			[
				'#type' => 'container',
				'#attributes' => [ 'class' => ['athex-table-footer'] ],
				[
					'#type' => 'html_tag',
					'#tag' => 'span',
					'#value' => $this->t('Data for the last 15 days')
				],
				[
					'#type' => 'html_tag',
					'#tag' => 'button',
					'#value' => $this->t('View All')
				]
			]
		];
	}

	private function renderRelatedInstrumentsTable() {
		return (new DataTable(
			[
				[ 'field' => 'symbol',	'label' => 'Symbol' ],
				[ 'field' => 'name',	'label' => 'Name' ],
				[ 'field' => 'insType',	'label' => 'Instrument' ]
			],
			$this->dummyReplicate([
				'symbol' => 'GD',
				'name' => 'GD',
				'insType' => 'Bond'
			])
		))->render();
	}

	private function renderSectionRowSnapshot() {
		return [
			[
				'#type' => 'html_tag',
				'#tag' => 'h2',
				'#value' => $this->t('Snapshot')
			],
			BsGrid::renderRow([
				$this->renderSummaryTable(),
				$this->renderSummaryChart()
			])
		];
	}

	public function render($product_id) {
		// throw new NotFoundHttpException();

		$page = new ProductPage([
			'product_type' => 'stocks',
			'product_id' => $product_id
		]);

		$page->addContent(
			BsGrid::renderContainer(
				$this->renderSectionRowSnapshot()
			)
		);

		$page->addCol('Stock Overview', 		$this->renderStockOverviewTable());
		$page->addCol('Trading Information', 	$this->renderTradingInfoTable());
		$page->addCol('Key Statistics', 		$this->renderKeyStatisticsTable());
		$page->addCol('Index Weight', 			$this->renderIndexWeightTable());
		$page->addCol('Historic Data', 			$this->renderHistoricDataTable());
		$page->addCol('Related Instruments', 	$this->renderRelatedInstrumentsTable());

		return $page->render();
	}
}
