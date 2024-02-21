<?php

namespace Drupal\athex_d_mde\AthexRendering;

use Drupal\Core\Url;

class VxBsNav
{

	public array $tabs;
	public ?string $seldTab;
	public string $class;
	public ?array $urls;
	public ?string $baseUrl;
	protected $productType;

	public function __construct(
		array   $tabs,
		string  $seldTab = null,
		string  $class = 'tabs',
		?array  $urls = null,
		?string $baseUrl = null // optional parameter for base URL
	) {
		$this->tabs = $tabs;
		$this->seldTab = $seldTab;
		$this->class = $class;
		$this->urls = $urls;
		$this->baseUrl = $baseUrl;
	}

	public function setProductType($productType) {
		$this->productType = !empty($productType) ? $productType : 'stocksearch22'; // Replace 'defaultType' with a valid default value
	}

	public function render()
	{
		$result = [
			'#type' => 'html_tag',
			'#tag' => 'ul',
			'#attributes' => [
				'class' => ['nav', 'nav-' . $this->class],
				'role' => 'tablist'
			]
		];

		foreach ($this->tabs as $idx => $label) {
			// Check if base URL is provided, construct URL with the letter as a query parameter
			$href = $this->baseUrl ? Url::fromRoute($this->baseUrl, ['productType' => $this->productType, 'letter' => $label])->toString() : '#';

			$aAttributes = [
				'class' => ['nav-link'],
				'href' => $href,
				'role' => 'tab',
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
}
