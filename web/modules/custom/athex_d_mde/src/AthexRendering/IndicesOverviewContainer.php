<?php

namespace Drupal\athex_d_mde\AthexRendering;

class IndicesOverviewContainer {

	protected $symbols;
	public readonly array $selectedData;

	public function __construct(
		array $symbols = [],
		array $selectedData = []
	) {
		$this->symbols = $symbols;
		$this->selectedData = $selectedData;
	}

	private function getIndexSummaryRA($data) {
		return [
			'#theme' => 'indices_overview_index_summary',
			'#cache' => [ 'max-age' => 9 ],
			...Helpers::hashtagKeys($data)
		];
	}

	private function renderTabContent(array $innerRA) {
		return [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#attributes' => [
				'role' => 'tabpanel'
			],
			$this->getIndexSummaryRA($this->selectedData),
			$innerRA
		];
	}

	public function render($innerRA) {
		return $this->renderTabContent($innerRA);
	}
}



// protected $symbols;
// public readonly Array $selectedData;

// public function __construct(
// 	Array $symbols = [],
// 	Array $selectedData = []
// ) {
// 	$this->symbols = $symbols;
// 	$this->selectedData = $selectedData;
// }

// private function getIndexSummaryRA() {
// 	return [
// 		'#theme' => 'indices_overview_index_summary',
// 		'#symbol' => $this->selectedData['symbol'],
// 		'#value' => $this->selectedData['value'],
// 		'#since_open_value' => $this->selectedData['since_open_value'],
// 		'#since_open_percentage' => $this->selectedData['since_open_percentage'],
// 		'#since_close_value' => $this->selectedData['since_close_value'],
// 		'#since_close_percentage' => $this->selectedData['since_close_percentage']
// 	];
// }

// private function getTabContentRA($innerRA) {
// 	return [
// 		'#type' => 'html_tag',
// 		'#tag' => 'div',
// 		'#attributes' => [
// 			'role' => 'tabpanel'
// 		],
// 		$this->getIndexSummaryRA(),
// 		$innerRA
// 	];
// }

// private function getTabsRA() {
// 	$bsNav = new BsNav($this->symbols, $this->selectedData['symbol']);
// 	return $bsNav->render();
// }

// public function render($innerRA) {
// 	return [
// 		'#type' => 'container',
// 		$this->getTabsRA(),
// 		$this->getTabContentRA($innerRA)
// 	];
// }
// }



