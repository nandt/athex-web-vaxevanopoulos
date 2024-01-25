<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_inbroker\Service\ApiDataService;


class IndicesOverviewTablesService {

	use StringTranslationTrait;

	protected $api;
	protected $containers;

	private $pills = [
		'risers' => 'Risers',
		'fallers' => 'Fallers',
		'active' => 'Most Active'
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
		return [
			'#theme' => 'table',
			'#rows' => [
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, 97.39, 1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, 97.39, 1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2],
				['ATG 10010', 'Lorem ipsum dolor sit amet', 7402.14, -97.39, -1.2]
			]
		];
	}

	private function getSubProductsPillsRA($seldTable) {
		return (new BsNav($this->pills, $seldTable, 'pills'))->render();
	}

	private function getSubProductsRA($seldSymbol, $seldTable = null) {
		if ($seldTable == null)
			$seldTable = array_keys($this->pills)[0];

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

	/*its for testing the above function */
	/*private function getSubProductsRA($seldSymbol, $seldTable) {
		return [
			'#theme' => 'table',
			'#rows' => [
				['Symbol', $seldSymbol]
			]
		];
	}
*/



	/* public function getBlockRA($seldTable = null) {
		$container = $this->containers->createContainer();
		var_dump($container->selectedData); // Debugging line
		return $container->render(
			$this->getSubProductsRA(
				$container->selectedData['symbol'],
				$seldTable
			)
		);
	}*/
	/*public function getBlockRA($seldTable = null) {
		$container = $this->containers->createContainer();

		$content = [];
		foreach ($container->selectedData as $data) {
			$content[] = $this->getSubProductsRA(
				$data['symbol'],
				$seldTable
			);
		}

		return $content;
	}
*/
	public function getBlockRA($seldTable = null) {
		$container = $this->containers->createContainer();
		$firstSymbolData = $container->selectedData[0] ?? null;

		if ($firstSymbolData) {
			return $container->render(
				$this->getSubProductsRA(
					$firstSymbolData['symbol'],
					$seldTable
				)
			);
		} else {
			// Handle the case where there is no data
			return ['#markup' => 'No data available.'];
		}
	}



	/*for one debugging */

	/*public function getBlockRA($seldTable = null) {
		// Hardcoded data for testing
		$testSymbol = 'GD.ATH';

		// Attempt to render a single block
		return $this->getSubProductsRA($testSymbol, $seldTable);
	}
*/

	/*public function getBlockRA($seldTable = null) {
		$container = $this->containers->createContainer();

		// Assuming you want to display all indices
		$allIndicesContent = [];
		foreach ($container->selectedData as $indexData) {
			// Now $indexData is an associative array for a single index
			$allIndicesContent[] = $this->getSubProductsRA(
				$indexData['symbol'], // Access the 'symbol' for each index
				$seldTable
			);
		}

		// Return all indices content. Adjust this part as per your requirement.
		return $allIndicesContent;
	}*/


}
