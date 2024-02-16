<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\athex_liferay_migrations\AthexData\ArticleDataIterator;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_articles_default_lang",
 *   source_module = "migrate"
 * )
 */
class ALArticlesDefaultLang extends SourcePluginBase {

	/**
	 * {@inheritdoc}
	 */
	public function fields() {
		return [
			'langcode' => 'langcode',

			'articleId' => 'articleId',
			'classNameId' => 'classNameId',
			'classPK' => 'classPK',
			'companyId' => 'companyId',
			'content' => 'content',
			'createDate' => 'createDate',
			'description' => 'description',
			'descriptionCurrentValue' => 'descriptionCurrentValue',
			'displayDate' => 'displayDate',
			'expirationDate' => 'expirationDate',
			'groupId' => 'groupId',
			'id' => 'id',
			'indexable' => 'indexable',
			'layoutUuid' => 'layoutUuid',
			'modifiedDate' => 'modifiedDate',
			'resourcePrimKey' => 'resourcePrimKey',
			'reviewDate' => 'reviewDate',
			'smallImage' => 'smallImage',
			'smallImageId' => 'smallImageId',
			'smallImageURL' => 'smallImageURL',
			'status' => 'status',
			'statusByUserId' => 'statusByUserId',
			'statusByUserName' => 'statusByUserName',
			'statusDate' => 'statusDate',
			'structureId' => 'structureId',
			'templateId' => 'templateId',
			'title' => 'title',
			'titleCurrentValue' => 'titleCurrentValue',
			'type' => 'type',
			'urlTitle' => 'urlTitle',
			'userId' => 'userId',
			'userName' => 'userName',
			'uuid' => 'uuid',
			'version' => 'version'
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new ArticleDataIterator(false);
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
			'resourcePrimKey' => [
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
