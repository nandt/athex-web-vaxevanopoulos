<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\Pager\Pager;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Url;
use Drupal\Core\Language\LanguageInterface;


class ProductSearch {

	use StringTranslationTrait;

	public readonly string $title;
	public array $secondaryFiltersRA;
	private Pager $pager;
	private string|null $seldLetter;

	public function __construct(string $enTitle, array $secondaryFiltersRA) {
		$this->title = $this->t($enTitle);
		$this->secondaryFiltersRA = $secondaryFiltersRA;
		$this->pager = \Drupal::service('pager.manager')->createPager(30, 10);
		$this->seldLetter = strtoupper(\Drupal::request()->get('letter') ?? '');
		if (!in_array($this->seldLetter, range('A', 'Z')))
			$this->seldLetter = null;
	}

	public function getCurrentProductType() {
		$route_match = \Drupal::service('current_route_match');
		$productType = $route_match->getParameter('productType');
		return $productType;
	}



	public function getResultsOffset() {
		return ($this->pager->getCurrentPage() - 1) * $this->pager->getLimit();
	}

	public function getResultsLimit() {
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


	/*private function getTabsRA()
	{
		$seldLetter = $this->seldLetter;
		if (!$seldLetter) $seldLetter = 'All';
		$options = ['All', ...range('A', 'Z')];

		// Define the base URL for the tabs. This should be the route name for the page where the tabs are displayed.
		// For example, if you have a route named 'athex_d_products.stock_search', use that.
		$baseUrl = 'athex_d_products.stock_search';

		// Create an instance of BsNav, passing the base URL as the last argument.
		$bsNav = new BsNav($options, $seldLetter, 'pills', null, $baseUrl);

		return $bsNav->render();
	}*/
	private function getTabsRA() {
		$seldLetter = $this->seldLetter;
		if (!$seldLetter) $seldLetter = 'All';
		$options = ['All', ...range('A', 'Z')];

		// Retrieve the current product type
		$productType = $this->getCurrentProductType();
//var_dump($productType);
		// Ensure the base URL includes the productType parameter
		// Adjust this part to ensure the productType is correctly passed to each tab link
		//$baseUrl = Url::fromRoute('athex_d_products.stock_search', ['productType' => $productType])->toString();
		$formAction = Url::fromRoute('athex_d_products.stock_search', ['productType' => $productType])
			->setOption('language', \Drupal::languageManager()->getLanguage(LanguageInterface::LANGCODE_NOT_SPECIFIED))
			->toString();

		$bsNav = new BsNav($options, $seldLetter, 'pills', null, $formAction);

		return $bsNav->render();
	}


	/*private function getSearchFormRA()
		 $productType = $this->getCurrentProductType(); // Get the current product type
          $formAction = Url::fromRoute('athex_d_products.stock_search', ['productType' => $productType])->toString();
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
	}*/
	/* that one was working before
	 * public function getSearchFormRA() {
		$productType = $this->getCurrentProductType(); // Get the current product type
		//$formAction = Url::fromRoute('athex_d_products.stock_search', ['productType' => $productType])->toString();
		$formAction = Url::fromRoute('athex_d_products.stock_search', ['productType' => $productType])
			->setOption('language', \Drupal::languageManager()->getLanguage(LanguageInterface::LANGCODE_NOT_SPECIFIED))
			->toString();
		\Drupal::logger('Prduct Search debuging url')->notice($formAction);

		return [
			'#type' => 'form',
			'#method' => 'GET',
			'#action' => $formAction,
			'#attributes' => ['class' => ['bef-exposed-form']],
			'#children' => [
				$this->getSearchbarRA(),
				$this->getTabsRA(),
				$this->getSecondaryFiltersRA(),
				// $this->getSubmitButton() // Uncomment if needed
			]
		];
	}
	*/
	public function getSearchFormRA(array $filters, array $filterValues) {

		$form = [
			'#type' => 'form',
			'#method' => 'GET',
			//'#action' => $this->getFormAction(),
			'#attributes' => ['class' => ['bef-exposed-form']],
			'#children' => [],
		];

		foreach ($filters as $filterKey => $filterDef) {
			$form[$filterKey] = [
				'#type' => $filterDef['type'],
				'#title' => $filterDef['title'],
				'#default_value' => $filterValues[$filterKey] ?? '',
				'#options' => $filterDef['options'] ?? null, // For select elements
				'#attributes' => ['placeholder' => $filterDef['placeholder'] ?? ''],
			];
		}

		$form['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Apply Filters'),
		];

		return $form;
	}


	private function getSecondaryFiltersRA()
	{
		return [
			'#type' => 'details',
			'#method' => 'GET',
			'#title' => $this->t('Filters'),
			'#open' => false,
			'#attributes' => ['class' => ['bef--secondary']],
			'market' => $this->getMarketField(), // Incorporates the checkboxes for market
			'price' => $this->getPriceField(), // Adds a fieldset for price range
			'submit' => $this->getSubmitButton(), // Adds the submit button
		];
	}


	//public function render(DataTable $table)
	/*public function render(DataTable $table, array $filterValues) {
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
*/

	/**/

	public function render(DataTable $table, array $filterValues, array $headers, array $filters) {
		\Drupal::logger('product_search')->debug('Rendering started...');

		\Drupal::logger('product_search')->debug('Calling getSearchFormRA...');
		try {
			$form = $this->getSearchFormRA($filters, $filterValues);
			\Drupal::logger('product_search form')->debug('<pre>' . print_r(gettype($form), TRUE) . '</pre>');
		} catch (\Exception $e) {
			\Drupal::logger('product_search')->error('Error during getSearchFormRA: ' . $e->getMessage());
			return;
		}

		\Drupal::logger('product_search')->debug('Calling DataTable::render...');
		try {
			if($table === null) {
				\Drupal::logger('product_search')->error('DataTable is null.');
				return;
			}

			$tableRender = $table->render();

			if($tableRender === null) {
				\Drupal::logger('product_search')->error('DataTable::render() returned null.');
				return;
			}

			\Drupal::logger('product_search table')->debug('<pre>' . print_r(gettype($tableRender), TRUE) . '</pre>');
		} catch (\Exception $e) {
			\Drupal::logger('product_search')->error('Error during DataTable::render: ' . $e->getMessage());
			return;
		}

		return [
			'#theme' => 'product_search',
			'#page_title' => $this->title,
			'#search_form' => $form,
			'#data' => $tableRender,
			'#pager' => ['#type' => 'pager'],
		];
	}


	/**/
	/*public function render(DataTable $table, array $filterValues, array $headers) {
		// Debugging
		\Drupal::logger('filterValues?')->notice('<pre>' . print_r($filterValues, TRUE) . '</pre>');
		\Drupal::logger('$headers?')->notice('<pre>' . print_r($headers, TRUE) . '</pre>');
		\Drupal::logger('filters?')->notice('<pre>' . print_r($filters, TRUE) . '</pre>');

		$form = $this->getSearchFormRA($this->filters, $filterValues);

		return [
			'#theme' => 'product_search',
			'#page_title' => $this->title,
			'#search_form' => $form,
			'#data' => $table->render($headers), // Ensure DataTable::render() method can accept headers
			'#pager' => ['#type' => 'pager'],
		];
	}*/


	public function getSearchForm() {
		return $this->getSearchFormRA();
	}
}
