<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;

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
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-pills'],
				'role' => 'tablist'
			]
		];

		foreach ($this->pills as $key => $label) {
			$aAttributes = [
				'class' => ['nav-link'],
				'href' => '#'
			];

			if ($key == $seldTable) {
				$aAttributes['class'][] = 'active';
				$aAttributes['aria-current'] = 'page';
			}

			$result[] = [
				'#type' => 'html_tag',
				'#tag' => 'li',
				'#attributes' => [
					'class' => ['nav-item']
				],
				[
					'#type' => 'html_tag',
					'#tag' => 'a',
					'#attributes' => $aAttributes,
					'#value' => $label
				]
			];
		}

		return $result;
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
