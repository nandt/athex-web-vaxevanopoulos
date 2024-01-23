<?php

namespace Drupal\athex_d_mde\AthexRendering;

class TabbedTable {

	public $tabs;
	public $data;

	public function __construct(
		Array $tabs = [],
		Array $data = []
	) {
		$this->tabs = $tabs;
		$this->data = $data;
	}

	private function renderTabs() {
		$bsNav = new BsNav($this->tabs);
		return $bsNav->render();
	}

	public function render() {
		return [
			'#type' => 'container',
			$this->renderTabs(),
			[
				'#type' => 'table',
				'#rows' => $this->data
			]
		];
	}
}
