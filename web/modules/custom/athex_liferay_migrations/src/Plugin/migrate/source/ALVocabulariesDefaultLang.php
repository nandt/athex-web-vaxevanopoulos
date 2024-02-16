<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;

use Drupal\athex_liferay_migrations\AthexData\LiferayVocabulariesIterator;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_vocabularies",
 *   source_module = "migrate"
 * )
 */
class ALVocabulariesDefaultLang extends SourcePluginBase {

	/**
	 * {@inheritdoc}
	 */
	public function fields() {
		return [
			"vocabularyId" => "vocabularyId",
			"name" => "name",
			"title" => "title",
			"titleCurrentValue" => "titleCurrentValue",
			"description" => "description",
			"descriptionCurrentValue" => "descriptionCurrentValue",
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new LiferayVocabulariesIterator();
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return 'Athex Liferay Vocabularies (Default Language)';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIds() {
		return [
			'vocabularyId' => [
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
