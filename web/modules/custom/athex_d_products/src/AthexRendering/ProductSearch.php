<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Url;
use Drupal\athex_d_mde\AthexRendering\BsNav;

class ProductSearch {
	use StringTranslationTrait;

	protected $title;
	protected $productType;
	public function __construct(string $title, string $productType) { // Modify this line
		$this->title = $title;
		$this->productType = $productType; // Set the product type
		$this->seldLetter = \Drupal::request()->query->get('letter', 'A'); // Default to 'A' if not set
	}

	/*public function render(array $data, array $headers, array $filters) {
		$dataTable = new DataTable($data, $headers);
		$searchForm = $this->getSearchFormRA($filters);
		$tabs = $this->getTabsRA($filters['letterFilter'] ?? '');

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_form' => $searchForm,
			'#tabs' => $tabs, // Adding the tabs to the render array
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}*/





	/*private function getSearchFormRA($filters) {
		$form = [
			'#type' => 'form',
			'#method' => 'get',
			//'#attributes' => ['class' => ['search-filters-form']],
			'#attributes' => ['class' => ['bef-exposed-form']],

		];

		foreach ($filters as $filterKey => $filterOptions) {
			// Ensure the title is a string before passing it to $this->t()
			// If the title is an object that implements __toString(), it can be cast to a string
			$title = (string) $filterOptions['title'];

			$form[$filterKey] = [
				'#type' => $filterOptions['type'],
				'#title' => $this->t($title),
				'#default_value' => $filterOptions['default_value'] ?? '',
				'#options' => $filterOptions['options'] ?? null,
				'#attributes' => $filterOptions['attributes'] ?? [],
			];

			// Handle attributes for 'select' element separately due to a different structure
			if ($filterOptions['type'] === 'select' && isset($filterOptions['options'])) {
				$form[$filterKey]['#options'] = $filterOptions['options'];
			}
		}

		$form['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Apply Filters'),
		];

		return $form;
	}*/
	private function getSearchFormRA($filters) {

		$form = [
			'#type' => 'form',
			'#method' => 'get',
			'#action' => Url::fromRoute('athex_d_products.stock_search', ['productType' => $this->productType])->toString(),
			'#attributes' => ['class' => ['bef-exposed-form']],
		];

		\Drupal::logger('PRODUCT SEARCH DEBUG PRODUCT TYPE')->notice('Product Type: ' . $this->productType);

		// Set the form action to include the 'productType'

		//$form['#action'] = Url::fromRoute('athex_d_products.stock_search')->toString();


	/*	foreach ($filters as $filterKey => $filterOptions) {
			$title = (string) $filterOptions['title'];
			$form[$filterKey]['#name'] = $filterKey;

			$form[$filterKey] = [
				'#type' => $filterOptions['type'],
				'#title' => $this->t($title),
				'#default_value' => $filterOptions['default_value'] ?? '',
				'#options' => $filterOptions['options'] ?? null,
				'#attributes' => $filterOptions['attributes'] ?? [],
			];

			if ($filterOptions['type'] === 'select' && isset($filterOptions['options'])) {
				$form[$filterKey]['#options'] = $filterOptions['options'];
			}
		}
*/
		foreach ($filters as $filterKey => $filterOptions) {
			$title = (string) $filterOptions['title'];

			$form[$filterKey] = [
				'#type' => $filterOptions['type'],
				'#title' => $this->t($title),
				'#default_value' => $filterOptions['default_value'] ?? '',
				'#options' => $filterOptions['options'] ?? null,
				'#attributes' => $filterOptions['attributes'] ?? [],
				'#name' => $filterKey, // This should be part of this array
			];
		}

		$form['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Apply Filters'),
		];

		return $form;
	}


/* new version of getTabsRA*/
/*	private function getTabsRA($selectedLetter) {
		$letters = ['All', ...range('A', 'Z')];
		$tabs = [];

		foreach ($letters as $letter) {
			$tabs[$letter] = [
				'title' => $letter,
				'url' => Url::fromRoute('athex_d_products.stock_search', ['letter' => $letter]),
				'active' => ($selectedLetter === $letter),
			];
		}

		return $tabs; // Adjust to match your theme's structure
	}
*/
	/*private function getAzNavigation($selectedLetter) {
		$letters = range('A', 'Z');
		$links = [];

		foreach ($letters as $letter) {
			// Assume 'your_route_name' is the route where the filter should be applied
			// You might need to add additional route parameters as needed
			$url = Url::fromRoute('your_route_name', ['letter' => $letter]);

			// Check if this letter is the selected one to add a specific class
			$options = $letter == $selectedLetter ? ['attributes' => ['class' => ['selected']]] : [];

			$links[] = [
				'#type' => 'link',
				'#title' => $letter,
				'#url' => $url,
				'#options' => $options,
			];
		}

		return [
			'#theme' => 'links',
			'#links' => $links,
			'#attributes' => ['class' => ['az-navigation']],
		];
	}*/



	private function getAzNavigation() {
		// Define the letters for the tabs
		$letters = range('A', 'Z');

		// Define the base URL for the tabs, adjust as needed
		$baseUrl = 'athex_d_products.stock_search';

		// Create the BsNav instance
		$bsNav = new BsNav($letters, $this->getSelectedLetter(), 'tabs', null, $baseUrl);

		// Set the productType for the BsNav instance
		$bsNav->setProductType($this->productType);

		// Generate the render array for the navigation
		return $bsNav->render();
	}

	protected function getSelectedLetter() {
		// Access the current request
		$current_request = \Drupal::request();

		// Get the 'letter' parameter from the URL
		$selectedLetter = $current_request->query->get('letter');

		// Ensure the selected letter is a single uppercase character if it's set, or default to 'A'
		return $selectedLetter && preg_match('/^[A-Z]$/', $selectedLetter) ? $selectedLetter : 'A';
	}
	/*public function render(array $data, array $headers, array $filters) {
		$dataTable = new DataTable($data, $headers);
		$searchForm = $this->getSearchFormRA($filters);
		$selectedLetter = $this->getSelectedLetter(); // Now this method exists
		$azNavigation = $this->getAzNavigation($selectedLetter);

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_form' => $searchForm,
			'#az_navigation' => $azNavigation, // Include the A to Z navigation
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}
*/
/* tha one works on filtes only not old template */
/*	public function render(array $data, array $headers, array $filters) {
		$dataTable = new DataTable($data, $headers);
		$searchForm = $this->getSearchFormRA($filters);
		$azNavigation = $this->getAzNavigation(); // Generate A to Z navigation

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_form' => $searchForm,
			'#az_navigation' => $azNavigation, // Include A to Z navigation in the render array
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}
*/

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
	private function getTabsRA() {
		$seldLetter = $this->seldLetter ?? 'All';
		//$seldLetter = $this->seldLetter;
		if (!$seldLetter) $seldLetter = 'All';
		$options = ['All', ...range('A', 'Z')];

		// Define the base URL for the tabs. This should be the route name for the page where the tabs are displayed.
		// For example, if you have a route named 'athex_d_products.stock_search', use that.
		$baseUrl = 'athex_d_products.stock_search';

		// Create an instance of BsNav, passing the base URL as the last argument.
		$bsNav = new BsNav($options, $seldLetter, 'pills', null, $baseUrl);

		// Now that $bsNav is instantiated, you can set the product type.
		$bsNav->setProductType($this->productType);

		return $bsNav->render();
	}

	public function render(array $data, array $headers, array $filters) {
		$dataTable = new DataTable($data, $headers);
		$searchForm = $this->getSearchFormRA($filters);
		$searchBar = $this->getSearchbarRA(); // Make sure this method exists and is implemented correctly
		$azNavigation = $this->getAzNavigation(); // This should already be implemented
		$tabs = $this->getTabsRA(); // Ensure this method is reintegrated and implemented

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_bar' => $searchBar, // Include the search bar
			'#az_navigation' => $azNavigation, // Include A to Z navigation
			//'#tabs' => $tabs, // Include the tabs
			'#tabs' => $this->getTabsRA(),
			'#search_form' => $searchForm,
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}



}
