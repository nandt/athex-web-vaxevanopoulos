<?php

namespace Drupal\athex_stock_pages\AthexSearch;

use Drupal\Core\StringTranslation\StringTranslationTrait;

class StockSearch {
	use StringTranslationTrait;

	private function getSearchbarRA() {
		return [
			'#type' => 'container',
			'#attributes' => [
				'class' => ['js-form-type-textfield']
			],
			[
				'#type' => 'textfield',
				'#attributes' => [
					'placeholder' => $this->t("Type here to search")
				]
			]
		];
	}

	private function getFilterRA($name) {
		return [
			'#type' => 'details',
			'#title' => $this->t($name)
		];
	}

	private function getSecondaryFiltersRA() {
		return [
			'#type' => 'details',
			'#title' => $this->t('Filters'),
			'#attributes' => [
				'class' => ['bef--secondary']
			],
			$this->getFilterRA('Market'),
			$this->getFilterRA('Industry'),
			$this->getFilterRA('Closing Price'),
			$this->getFilterRA('Date Range')
		];
	}

	private function getTabsRA($seldLetter) {
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-pills'],
				'role' => 'tablist'
			]
		];

		//TODO: $config->get('indices_overview_tabs');
		$options = ['All', ...range('A', 'Z')];

		foreach ($options as $opt) {
			$aAttributes = [
				'class' => ['nav-link'],
				'href' => '#'
			];

			if ($opt == $seldLetter) {
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
					'#value' => $opt
				]
			];
		}

		return $result;
	}

	private function getSearchFormRA() {
		return [
			'#type' => 'form',
			'#attributes' => [
				'class' => ['bef-exposed-form']
			],
			'#children' => [
				$this->getSearchbarRA(),
				$this->getTabsRA('All'),
				$this->getSecondaryFiltersRA()
			]
		];
	}

	private function getRows() {
		$result = [];
		for ($i = 0; $i < 10; $i++) {
			$result[] = [
				'ATG 10010',
				'ABN AMRO BANK N.V.',
				'NL0000852564',
				'ALTERNATIVE',
				'EUR 29.33',
				'3.56%',
				'25/10/2023 10:28 CEST'
			];
		}
		return $result;
	}

	private function getTableRA() {
		return [
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
			'#rows' => $this->getRows()
		];
	}

	public function render() {
		/** @var \Drupal\Core\Pager\Pager $pager */
		$pager = \Drupal::service('pager.manager')->createPager(30, 10);

		// $pager->getCurrentPage();
		// $data = $this->data->getResultData()

		return [
			//TODO: refine
			'#cache' => [ 'max-age' => 0 ],
			//
			'#theme' => 'stock_search',
			'#search_form' => $this->getSearchFormRA(),
			'#table' => $this->getTableRA(),
			'#pager' => [ '#type' => 'pager' ],
		];
	}
}
