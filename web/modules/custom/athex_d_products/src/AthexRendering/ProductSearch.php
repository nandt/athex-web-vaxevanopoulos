<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\Pager\Pager;
use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\DataTable;


class ProductSearch {

	use StringTranslationTrait;

	public readonly string $title;
	public array $secondaryFiltersRA;
	private Pager $pager;
	private string|null $seldLetter;

	public function __construct(
		string $enTitle,
		array $secondaryFiltersRA
	) {
		$this->title = $this->t($enTitle);
		$this->secondaryFiltersRA = $secondaryFiltersRA;
		$this->pager = \Drupal::service('pager.manager')->createPager(30, 10);
		$this->seldLetter = strtoupper(\Drupal::request()->get('letter') ?? '');
		if (!in_array($this->seldLetter, range('A', 'Z')))
			$this->seldLetter = null;
	}

	public function getResultsOffset() {
		return ($this->pager->getCurrentPage() - 1) * $this->pager->getLimit();
	}

	public function getResultsLimit() {
		return $this->pager->getLimit();
	}

	private function getSearchbarRA() {
		return [
			'#type' => 'container',
			'#attributes' => ['class' => ['js-form-type-textfield']],
			[
				'#type' => 'textfield',
				'#name' => 'search_value',  // Add a name attribute
				'#attributes' => ['placeholder' => $this->t("Type here to search")]
			]
		];
	}


	private function getSecondaryFiltersRA() {
		return array_merge([
			'#type' => 'details',
			'#title' => $this->t('Filters'),
			'#attributes' => [
				'class' => ['bef--secondary']
			]
		], $this->secondaryFiltersRA);
	}

	private function getTabsRA() {
		$seldLetter = $this->seldLetter;
		if (!$seldLetter) $seldLetter = 'All';
		$options = ['All', ...range('A', 'Z') ];
		$bsNav = new BsNav($options, $seldLetter, 'pills');
		return $bsNav->render();
	}

	private function getSearchFormRA() {
		return [
			'#type' => 'form',
			'#method' => 'GET', // Ensure the form is submitted using the GET method
			'#attributes' => [
				'class' => ['bef-exposed-form']
			],
			'#children' => [
				$this->getSearchbarRA(),
				$this->getTabsRA(),
				$this->getSecondaryFiltersRA()
			]
		];
	}

	public function render(
		DataTable $table
	) {
		return [
			//TODO: refine
			'#cache' => [ 'max-age' => 0 ],
			//
			'#theme' => 'product_search',
			'#page_title' => [
				'#type' => 'html_tag',
				'#tag' => 'h1',
				'#value' => $this->title
			],
			'#search_form' => $this->getSearchFormRA(),
			'#table' => $table->render(),
			'#pager' => [ '#type' => 'pager' ],
		];
	}
}
