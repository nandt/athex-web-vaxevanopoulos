<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryDataIterator;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\CategoryArticles\CategoryArticlesIterator;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_article_categories",
 *   source_module = "migrate"
 * )
 */
class ALArticleCategories extends SourcePluginBase {

	/**
	 * {@inheritdoc}
	 */
	public function fields() {
		return [
			"categoryId" => "categoryId",

			"classPK" => "classPK"
			// "classNameId": 10108,
			// "classPK": 24403,
			// "classTypeId": 0,
			// "classUuid": "e6715883-e466-48b9-a32a-0533d48a6ac7",
			// "companyId": 10154,
			// "createDate": 1697548735000,
			// "description": "",
			// "descriptionCurrentValue": "",
			// "endDate": null,
			// "entryId": 24405,
			// "expirationDate": null,
			// "groupId": 10180,
			// "height": 0,
			// "layoutUuid": "",
			// "mimeType": "text/html",
			// "modifiedDate": 1697548805000,
			// "priority": 0,
			// "publishDate": 1697548620000,
			// "startDate": null,
			// "summary": "",
			// "summaryCurrentValue": "",
			// "title": "< ? xml version='1.0' encoding='UTF-8' ? ><root available-locales=\"en_US,el_GR,\" default-locale=\"en_US\"><Title language-id=\"en_US\">test article (categorized by tag and category)</Title><Title language-id=\"el_GR\">Δοκιμαστικό Άρθρο (κατηγοριοποιημένο)</Title></root>",
			// "titleCurrentValue": "test article (categorized by tag and category)",
			// "url": "",
			// "userId": 10196,
			// "userName": "Test Test",
			// "viewCount": 9,
			// "visible": true,
			// "width": 0
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new AssetEntryDataIterator(new CategoryArticlesIterator(), false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return 'Athex Liferay Article Categories';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIds() {
		return [
			'categoryId' => [
				'type' => 'integer'
			],
			'classPK' => [
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
