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
			...Helpers::hashtagKeys($data)
		];
	}

	private function renderTabs() {
		$bsNav = new BsNav(array_column($this->selectedData, 'symbol'));
		return $bsNav->render();
	}

	private function renderTabContent() {
		$content = [];
		foreach ($this->selectedData as $index => $data) {
			// Assign a unique ID to each tab content
			$contentId = 'tab-content-' . $index;
			$content[] = [
				'#type' => 'html_tag',
				'#tag' => 'div',
				'#attributes' => ['id' => $contentId, 'class' => ['tab-content']],
				'content' => $this->getIndexSummaryRA($data),
			];
		}
		return $content;
	}

	public function render() {
		$tabs = $this->renderTabs();
		$content = $this->renderTabContent();

		// Debugging
		// \Drupal::logger('my_module')->notice('Tabs: ' . print_r($tabs, TRUE));
		// \Drupal::logger('my_module')->notice('Content: ' . print_r($content, TRUE));

		$build = [
			'#type' => 'container',
			'tabs' => $tabs,
			'content' => $content,
			'#attached' => [
				'library' => [
					'athex_d_mde/tab-switching',
					'core/once',
				],
			],
		];

		return $build;
	}



}
