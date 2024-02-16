<?php

namespace Drupal\athex_d_products\AthexRendering;



use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\athex_d_mde\AthexRendering\DataTable;

class ProductSearch {
	use StringTranslationTrait;

	protected $title;

	public function __construct(string $title) {
		$this->title = $title;
	}

	public function render(array $data, array $headers, array $filters) {
		$dataTable = new DataTable($data, $headers);

		// Assume getSearchFormRA() returns a render array for the search form
		$searchForm = $this->getSearchFormRA($filters);

		return [
			'#theme' => 'product_search',
			'#title' => $this->t($this->title),
			'#search_form' => $searchForm,
			'#table' => $dataTable->render(),
			'#pager' => ['#type' => 'pager'],
		];
	}

	private function getSearchFormRA($filters) {
		$form = [
			'#type' => 'form',
			'#method' => 'get', // Use GET method to append filter parameters to the URL.
			'#attributes' => ['class' => ['search-filters-form']],
		];

		// Dynamically add filter elements based on the provided filters
		foreach ($filters as $filterKey => $filterOptions) {
			// Ensure the title is a string before passing it to $this->t()
			$title = is_string($filterOptions['title']) ? $filterOptions['title'] : '';

			$form[$filterKey] = [
				'#type' => $filterOptions['type'],
				'#title' => $this->t($title), // Use the sanitized $title variable here

				'#default_value' => isset($filterOptions['default_value']) ? $filterOptions['default_value'] : '',
				'#size' => $filterOptions['size'] ?? 30,
				'#maxlength' => $filterOptions['maxlength'] ?? 255,
				'#options' => $filterOptions['options'] ?? null, // For select elements
				'#description' => $filterOptions['description'] ?? '',
				'#attributes' => ['placeholder' => $filterOptions['placeholder'] ?? ''],
			];

			// Special handling for checkboxes and radios to ensure proper structure
			if (in_array($filterOptions['type'], ['checkboxes', 'radios']) && isset($filterOptions['options'])) {
				unset($form[$filterKey]['#default_value']);
				unset($form[$filterKey]['#size']);
				unset($form[$filterKey]['#maxlength']);
			}
		}

		// Add a submit button to the form
		$form['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Apply Filters'),
		];

		return $form;
	}


}
