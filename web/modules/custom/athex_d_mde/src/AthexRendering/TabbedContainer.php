<?php

namespace Drupal\athex_d_mde\AthexRendering;


class TabbedContainer {

	public $tabs;

	public function __construct(
		array $tabs = []
	) {
		$this->tabs = $tabs;
	}

	private function renderTabs() {
		$bsNav = new BsNav($this->tabs);
		return $bsNav->render();
	}

	public function render($render) {
		return [
			'#type' => 'container',
			$this->renderTabs(),
			$render
		];
	}
}
