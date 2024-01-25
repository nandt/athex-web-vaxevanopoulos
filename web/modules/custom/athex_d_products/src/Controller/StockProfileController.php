<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\athex_d_mde\AthexRendering\PropertyTable;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\athex_d_products\ProductType;
use Drupal\athex_d_products\AthexRendering\BsGrid;
use Drupal\athex_d_products\AthexRendering\ProductPage;
use Drupal\athex_d_products\Service\StockDataService;


class StockProfileController extends ControllerBase {

	protected $data;

	public function __construct(
		StockDataService $data
	) {
		$this->data = $data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.stock_data')
		);
	}

	private function dummyReplicate($data) {
		$result = [];
		for ($i = 0; $i < 6; $i++) {
			$result[] = $data;
		}
		return $result;
	}

	private function renderSummaryTable() {
		return (new PropertyTable(
			[
				'SummaryTableKey' => 'SummaryTableENLabel'
			],
			[
				'SummaryTableKey' => ['SummaryTableValue']
			]
		))->render();
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
		return (new DataTable(
			[
				[ 'field' => 'index',	'label' => 'Index' ],
				[ 'field' => 'weight',	'label' => '%' ]
			],
			$this->dummyReplicate([
				'index' => 'GD',
				'weight' => '7'
			])
		))->render();
	}

	private function renderHistoricDataTable() {
		return (new DataTable(
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
		))->render();
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
			'product_type' => 'stock',
			'product_id' => $product_id
		]);

		$page->addContent(
			BsGrid::renderContainer(
				$this->renderSectionRowSnapshot()
			)
		);

		$page->addSection('Stock Overview', 		$this->renderStockOverviewTable());
		$page->addSection('Trading Information', 	$this->renderTradingInfoTable());
		$page->addSection('Key Statistics', 		$this->renderKeyStatisticsTable());
		$page->addSection('Index Weight', 			$this->renderIndexWeightTable());
		$page->addSection('Historic Data', 			$this->renderHistoricDataTable());
		$page->addSection('Related Instruments', 	$this->renderRelatedInstrumentsTable());

		return $page->render();
	}
}
