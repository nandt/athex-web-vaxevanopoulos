<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\athex_d_mde\AthexRendering\DataTable;
use Drupal\Core\Url;
use Drupal\athex_d_mde\AthexRendering\BsNav;

class ProductSearch
{
	use StringTranslationTrait;

	protected $title;
	protected $productType;

	public function __construct(string $title, string $productType)
	{ // Modify this line
		$this->title = $title;
		$this->productType = $productType; // Set the product type
		$this->seldLetter = \Drupal::request()->query->get('letter', 'A'); // Default to 'A' if not set
	}
	private function getSearchFormRA($filters)
	{
		$form = [
			'#type' => 'form',
			'#method' => 'get',
			'#action' => Url::fromRoute('athex_d_products.stock_search', ['productType' => $this->productType])->toString(),
			'#attributes' => ['class' => ['bef-exposed-form']],
		];

		\Drupal::logger('PRODUCT SEARCH DEBUG PRODUCT TYPE')->notice('Product Type: ' . $this->productType);


		foreach ($filters as $filterKey => $filterOptions) {
			$title = (string)$filterOptions['title'];

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
		$form['tabs'] = $this->getTabsRA();
		return $form;
	}

	private function getAzNavigation()
	{
		$letters = ['All', ...range('A', 'Z')];
		$selectedLetter = $this->getSelectedLetter();
		$baseUrl = 'athex_d_products.stock_search'; // Adjust this to your actual route

		$bsNav = new BsNav($letters, $selectedLetter, 'tabs', null, $baseUrl);
		$bsNav->setProductType($this->productType); // Ensure this is set before rendering
		\Drupal::logger('PRODUCT SEARCH FOR LETTERS')->notice('getAzNavigation called');
		return $bsNav->render();
	}


	protected function getSelectedLetter()
	{
		// Access the current request
		$current_request = \Drupal::request();

		// Get the 'letter' parameter from the URL
		$selectedLetter = $current_request->query->get('letter');

		// Ensure the selected letter is a single uppercase character if it's set, or default to 'All'
		return $selectedLetter && preg_match('/^[A-Z]$/', $selectedLetter) ? $selectedLetter : 'All';
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


	private function getTabsRA()
	{
		$selectedLetter = $this->getSelectedLetter();
		$options = ['All', ...range('A', 'Z')];
		$baseUrl = 'athex_d_products.stock_search';
		$tabs = [];
		$bsNav = new BsNav($options, $selectedLetter, 'pills', null, $baseUrl);
		$bsNav->setProductType($this->productType); // Make sure this property is defined and set in your class
		foreach ($options as $option) {
			// Create the URL for each tab, including the letter as a query parameter
			$url = Url::fromRoute($baseUrl, ['productType' => $this->productType], ['query' => ['letter' => $option]]);

			// Check if the current option is the selected letter
			$isActive = ($selectedLetter === $option);

			$tabs[] = [
				'title' => $option,
				'url' => $url->toString(),
				'active' => $isActive,
			];
		}

		return $bsNav->render();
	}


	public function render(array $data, array $headers, array $filters)
	{
		$dataTable = new DataTable($data, $headers);
		$searchForm = $this->getSearchFormRA($filters);

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_form' => $searchForm,
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}

}
