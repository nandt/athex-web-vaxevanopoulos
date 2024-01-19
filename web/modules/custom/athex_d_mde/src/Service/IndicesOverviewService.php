<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

use Drupal\athex_inbroker\Service\ApiDataService;


class IndicesOverviewService {

	protected $logger;
	protected $config;
	protected $api;

	private $pills = [
		'risers' => 'Risers',
		'fallers' => 'Fallers',
		'active' => 'Most Active'
	];

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface $configFactory,
		ApiDataService $api
	) {
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->config = $configFactory->get('athex_d_mde.settings');
		$this->api = $api;
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
			$this->getSubProductsTableRA($seldSymbol, $seldTable)
		];
	}

	private function getIndexSummaryRA($seldSymbol) {
		//TODO: get data from API
		return [
			'#theme' => 'indices_overview_index_summary',
			'#symbol' => $seldSymbol,
			'#value' => 1167.93,
			'#since_open_value' => 17.89,
			'#since_open_percentage' => 1.46,
			'#since_close_value' => 204.11,
			'#since_close_percentage' => 6.05
		];
	}

	private function getTabContentRA($seldSymbol, $seldTable) {
		return [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#attributes' => [
				'role' => 'tabpanel'
			],
			$this->getIndexSummaryRA($seldSymbol),
			$this->getSubProductsRA($seldSymbol, $seldTable)
		];
	}

	private function getTabsRA($seldSymbol) {
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-tabs'],
				'role' => 'tablist'
			]
		];

		//TODO: $config->get('indices_overview_tabs');
		$indices = ['GD', 'FTSE', 'FTSEM', 'FTSED', 'ATHEX ESG', 'FTSEMSFW'];

		foreach ($indices as $index) {
			$aAttributes = [
				'class' => ['nav-link'],
				'href' => '#'
			];

			if ($index == $seldSymbol) {
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
					'#value' => $index
				]
			];
		}

		return $result;
	}

	public function getBlockRA($seldSymbol = null, $seldTable = null) {
		if ($seldSymbol == null)
			//TODO: $config->get('indices_overview_tabs')[0];
			$seldSymbol = 'GD';

		return [
			'#type' => 'container',
			$this->getTabsRA($seldSymbol),
			$this->getTabContentRA($seldSymbol, $seldTable)
		];
	}

}
