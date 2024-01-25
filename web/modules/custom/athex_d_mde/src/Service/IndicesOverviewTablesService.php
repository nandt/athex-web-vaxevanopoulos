<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\ProductsTable;
use Drupal\athex_inbroker\Service\ApiDataService;


class IndicesOverviewTablesService {

	use StringTranslationTrait;

	protected $api;
	protected $containers;

	private $pills = [
		'Risers',
		'Fallers',
		'Most Active'
	];

	public function __construct(
		ApiDataService $api,
		IndicesOverviewService $containers
	) {
		$this->api = $api;
		$this->containers = $containers;
  	}

	private function getSubProductsTableRA($seldSymbol, $seldTable) {
		//TODO: get data from API
		return (new ProductsTable([
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, 97.39, 1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, 97.39, 1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
			['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2]
		]))->render();
	}

	private function getSubProductsPillsRA($seldTable) {
		return (new BsNav($this->pills, $seldTable, 'pills'))->render();
	}

	private function getSubProductsRA($seldSymbol, $seldTable = null) {
		if ($seldTable == null)
			$seldTable = $this->pills[0];

		return [
			'#type' => 'container',
			$this->getSubProductsPillsRA($seldTable),
			$this->getSubProductsTableRA($seldSymbol, $seldTable),
			[
				'#type' => 'link',
				'#title' => $this->t('Explore More'),
				'#url' => \Drupal\Core\Url::fromUri('internal:#')
			]
		];
	}

	public function getBlockRA($seldTable = null) {
		$container = $this->containers->createContainer();
		return $container->render(
			$this->getSubProductsRA(
				$container->selectedData['symbol'],
				$seldTable
			)
		);
	}
}
