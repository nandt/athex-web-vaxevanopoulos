<?php

namespace Drupal\athex_d_mde\AthexRendering;
use Drupal\Core\Url;
class BsNav {

	public array $tabs;
	public ?string $seldTab;
	public string $class;
	public ?array $urls;

	public function __construct(
		array $tabs,
		string $seldTab = null,
		string $class = 'tabs',
		array|null $urls = null
	) {
		$this->tabs = $tabs;
		$this->seldTab = $seldTab;
		$this->class = $class;
		$this->urls = $urls;
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

		foreach ($this->tabs as $idx => $label) {
			// Create a unique href ID for each tab content
			$hrefId = 'tab-content-' . $idx;

			$aAttributes = [
				'class' => ['nav-link'],
				'href' => '#' . $hrefId, // Point to the unique content ID
				'data-bs-toggle' => 'tab',
				'role' => 'tab',
				'aria-controls' => $hrefId,
				'aria-selected' => 'false'
			];

			if ($label == $this->seldTab) {
				$aAttributes['class'][] = 'active';
				$aAttributes['aria-selected'] = 'true';
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


	/*public function render() {
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-' . $this->class],
				'role' => 'tablist'
			]
		];

		foreach ($this->tabs as $idx => $label) {
			// Assuming your route for filtering by letter is the same as the current route
			$current_path = \Drupal::service('path.current')->getPath();
			$url = Url::fromUserInput($current_path, ['query' => ['letter' => $label]]);

			$aAttributes = [
				'class' => ['nav-link'],
				'href' => $url->toString(), // Use the Url object to get the string representation
				'role' => 'tab',
			];

			if ($label == $this->seldTab) {
				$aAttributes['class'][] = 'active';
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
	}*/


}
