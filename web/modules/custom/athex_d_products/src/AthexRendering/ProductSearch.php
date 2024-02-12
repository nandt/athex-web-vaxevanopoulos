<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\Pager\Pager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\DataTable;

class ProductSearch
{

	use StringTranslationTrait;

	public readonly string $title;
	public array $secondaryFiltersRA;
	private Pager $pager;
	private string|null $seldLetter;

	public function __construct(string $enTitle, array $secondaryFiltersRA)
	{
		$this->title = $this->t($enTitle);
		$this->secondaryFiltersRA = $secondaryFiltersRA;
		$this->pager = \Drupal::service('pager.manager')->createPager(30, 10);
		$this->seldLetter = strtoupper(\Drupal::request()->get('letter') ?? '');
		if (!in_array($this->seldLetter, range('A', 'Z')))
			$this->seldLetter = null;
	}

	public function getResultsOffset()
	{
		return ($this->pager->getCurrentPage() - 1) * $this->pager->getLimit();
	}

	public function getResultsLimit()
	{
		return $this->pager->getLimit();
	}

	private function getSearchbarRA()
	{
		return [
			'#type' => 'container',
			'#attributes' => ['class' => ['js-form-type-textfield']],
			[
				'#type' => 'textfield',
				'#name' => 'search_value',
				'#attributes' => ['placeholder' => $this->t("Type here to search")]
			]
		];
	}

	private function getMarketField()
	{
		// Use the prepared options array for the checkboxes
		$marketOptions = [
			'30117' => 'SECURITIES MARKET',
			'30250' => 'ALTERNATIVE MARKET',
			// Add more options as needed
		];


		return [

			'#type' => 'container',
			'#attributes' => ['class' => ['js-form-type-textfield']],
			[
				'#title' => $this->t('Market'),
				'#type' => 'select',

				'#options' => $marketOptions,
				//'#title_display' => $this->t('Closing Price Range23'),
				'#id' => 'market-checkboxes', // Ensure this ID is unique if it's needed for CSS or JavaScript
				'#name' => 'market',
			]
		];

	}


	private function getPriceField()
	{
		return [
			'#type' => 'fieldset',
			'#title' => $this->t('Closing Price Range'),
			'minPrice' => [
				'#type' => 'textfield',
				'#title' => $this->t('Min Price'),
				'#size' => 10,
				'#name' => 'minPrice',
			],
			'maxPrice' => [
				'#type' => 'textfield',
				'#title' => $this->t('Max Price'),
				'#size' => 10,
				'#name' => 'maxPrice',
			],
		];
	}

	private function getSubmitButton()
	{
		return [
			'#type' => 'submit',
			'#value' => $this->t('Apply Filters'),
		];
	}

	private function getTabsRA()
	{
		$seldLetter = $this->seldLetter;
		if (!$seldLetter) $seldLetter = 'All';
		$options = ['All', ...range('A', 'Z')];
		$bsNav = new BsNav($options, $seldLetter, 'pills');
		return $bsNav->render();
	}

	private function getSearchFormRA()
	{
		$formAction = \Drupal\Core\Url::fromRoute('athex_d_products.stock_search')->toString();

		return [
			'#type' => 'form',
			'#method' => 'GET',
			'#action' => $formAction,
			'#attributes' => ['class' => ['bef-exposed-form']],
			'#children' => [
				$this->getSearchbarRA(),
				$this->getTabsRA(),
				$this->getSecondaryFiltersRA(),
				//$this->getSubmitButton()
			]
		];
	}

	private function getSecondaryFiltersRA()
	{
		return [
			'#type' => 'details',
			'#method' => 'GET',
			'#title' => $this->t('Filters'),
			'#open' => true,
			'#attributes' => ['class' => ['bef--secondary']],
			'market' => $this->getMarketField(), // Incorporates the checkboxes for market
			'price' => $this->getPriceField(), // Adds a fieldset for price range
			'submit' => $this->getSubmitButton(), // Adds the submit button
		];
	}


	public function render(DataTable $table)
	{
		return [
			'#cache' => ['max-age' => 6],
			'#theme' => 'product_search',
			'#page_title' => [
				'#type' => 'html_tag',
				'#tag' => 'h1',
				'#value' => $this->title
			],
			'#search_form' => $this->getSearchFormRA(),
			'#table' => $table->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}
}
