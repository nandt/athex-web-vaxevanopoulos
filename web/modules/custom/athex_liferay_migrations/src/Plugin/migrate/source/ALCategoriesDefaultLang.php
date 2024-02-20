<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\Categories\CategoriesIterator;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_categories",
 *   source_module = "migrate"
 * )
 */
class ALCategoriesDefaultLang extends SourcePluginBase {

	/**
	 * {@inheritdoc}
	 */
	public function fields() {
		return [
			"categoryId" => "categoryId",
			"name" => "name",
			"title" => "title",
			"titleCurrentValue" => "titleCurrentValue",
			"description" => "description",
			"descriptionCurrentValue" => "descriptionCurrentValue",
			"parentCategoryId" => "parentCategoryId",
			"vocabularyId" => "vocabularyId",
			// "categoryId": 1722038,
			// "companyId": 10154,
			// "createDate": 1391432178521,
			// "description": "",
			// "descriptionCurrentValue": "",
			// "groupId": 10180,
			// "leftCategoryId": 2420,
			// "modifiedDate": 1686907103288,
			// "name": "2. Change in voting rights",
			// "parentCategoryId": 0,
			// "rightCategoryId": 2421,
			// "title": "< ? xml version='1.0' encoding='UTF-8' ? ><root available-locales=\"en_US,el_GR,\" default-locale=\"en_US\"><Title language-id=\"en_US\">2. Change in voting rights</Title><Title language-id=\"el_GR\">2. Μεταβολή ποσοστών ψήφου</Title></root>",
			// "titleCurrentValue": "2. Μεταβολή ποσοστών ψήφου",
			// "userId": 12622,
			// "userName": "Liana Petrogona",
			// "uuid": "68d4bcd5-aee4-415d-b5a3-fd6d4c0dfa02",
			// "vocabularyId": 1722027
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new CategoriesIterator();
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return 'Athex Liferay Categories (Default Language)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIds() {
		return [
			'categoryId' => [
				'type' => 'integer'
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function count($refresh = FALSE) {
		return MigrateSourceInterface::NOT_COUNTABLE;
	}
}
