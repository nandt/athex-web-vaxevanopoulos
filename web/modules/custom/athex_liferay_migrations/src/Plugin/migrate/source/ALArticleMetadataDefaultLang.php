<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryDataIterator;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\AssetEntries\AssetEntryIterator;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_article_metadata_default_lang",
 *   source_module = "migrate"
 * )
 */
class ALArticleMetadataDefaultLang extends SourcePluginBase {

	/**
	 * {@inheritdoc}
	 */
	public function fields() {
		return [
			'langcode' => 'langcode',
			'classNameId' => 'classNameId',
			'classPK' => 'classPK',
			'classTypeId' => 'classTypeId',
			'classUuid' => 'classUuid',
			'companyId' => 'companyId',
			'createDate' => 'createDate',
			'description' => 'description',
			'descriptionCurrentValue' => 'descriptionCurrentValue',
			'endDate' => 'endDate',
			'entryId' => 'entryId',
			'expirationDate' => 'expirationDate',
			'groupId' => 'groupId',
			'height' => 'height',
			'layoutUuid' => 'layoutUuid',
			'mimeType' => 'mimeType',
			'modifiedDate' => 'modifiedDate',
			'priority' => 'priority',
			'publishDate' => 'publishDate',
			'startDate' => 'startDate',
			'summary' => 'summary',
			'summaryCurrentValue' => 'summaryCurrentValue',
			'title' => 'title',
			'titleCurrentValue' => 'titleCurrentValue',
			'url' => 'url',
			'userId' => 'userId',
			'userName' => 'userName',
			'viewCount' => 'viewCount',
			'visible' => 'visible',
			'width' => 'width',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new AssetEntryDataIterator(new AssetEntryIterator(10108), false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return 'Athex Liferay Articles (Default Language)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIds() {
		return [
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
