<?php

namespace Drupal\athex_d_products\Controller;

use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Drupal\athex_d_products\ProductType;
use Drupal\athex_d_products\AthexRendering\BsGrid;
use Drupal\athex_d_products\Service\ProductPageLayoutService;
use Drupal\athex_d_products\Service\StockDataService;


class StockProfileController extends ControllerBase {

	private static $sectionNames = [
		'Snapshot' => 'Snapshot',
		'StockOverview' => 'Stock Overview',
		'TradingInfo' => 'Trading Information',
		'KeyStatistics' => 'Key Statistics',
		'IndexWeight' => 'Index Weight',
		'HistoricData' => 'Historic Data',
		'RelatedInstruments' => 'Related Instruments'
	];

	protected $layout;
	protected $data;

	public function __construct(
		ProductPageLayoutService $layout,
		StockDataService $data
	) {
		$this->layout = $layout;
		$this->data = $data;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_d_products.layout'),
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
		return [
			'#type' => 'table',
			'#rows' => $this->dummyReplicate([
				'SummaryTable', 'SummaryTable'
			])
		];
	}

	private function renderSummaryChart() {
		return [
			'#type' => 'table',
			'#rows' => [
				[ 'SummaryChart' ]
			]
		];
	}

	private function renderStockOverviewTable() {
		return [
			'#type' => 'table',
			'#rows' => $this->dummyReplicate([
				'StockOverviewTable', 'StockOverviewTable'
			])
		];
	}

	private function renderTradingInfoTable() {
		return [
			'#type' => 'table',
			'#rows' => $this->dummyReplicate([
				'TradingInfoTable', 'TradingInfoTable'
			])
		];
	}

	private function renderKeyStatisticsTable() {
		return [
			'#type' => 'table',
			'#rows' => $this->dummyReplicate([
				'KeyStatisticsTable', 'KeyStatisticsTable'
			])
		];
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
		$section = 'Snapshot';
		return [
			[
				'#type' => 'html_tag',
				'#tag' => 'h2',
				'#value' => $this->t(
					self::$sectionNames[$section] ?? $section
				)
			],
			BsGrid::renderRow([
				$this->renderSummaryTable(),
				$this->renderSummaryChart()
			])
		];
	}

	private function renderSection(string $section) {
		$fn = "render{$section}Table";

		if (!method_exists($this, $fn))
			return [];

		return [
			[
				'#type' => 'html_tag',
				'#tag' => 'h2',
				'#value' => $this->t(
					self::$sectionNames[$section] ?? $section
				)
			],
			$this->$fn()
		];
	}

	public function render($product_id) {
		// throw new NotFoundHttpException();

		return $this->layout->render(
			ProductType::STOCK, $product_id,
			[
				BsGrid::renderContainer(
					$this->renderSectionRowSnapshot()
				),
				BsGrid::renderContainer([
					BsGrid::renderRow([
						$this->renderSection('StockOverview'),
						$this->renderSection('TradingInfo'),
					]),
					BsGrid::renderRow([
						$this->renderSection('KeyStatistics'),
						$this->renderSection('IndexWeight'),
					]),
					BsGrid::renderRow([
						$this->renderSection('HistoricData'),
						$this->renderSection('RelatedInstruments')
					])
				])
			]
		);
	}
}
