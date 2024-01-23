<?php

namespace Drupal\athex_d_mde\AthexRendering;

class BsNav {

	public $tabs;
	public $seldTab;
	public $class;

	public function __construct(
		Array $tabs = [],
		string $seldTab = null,
		string $class = 'tabs'
	) {
		$this->tabs = $tabs;
		$this->seldTab = $seldTab;
		$this->class = $class;
	}

	public function render() {
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-' . $this->class],
				'role' => 'tablist'
			]
		];

		foreach ($this->tabs as $label) {
			$aAttributes = [
				'class' => ['nav-link'],
				'href' => '#'
			];

			if ($label == $this->seldTab) {
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
}
