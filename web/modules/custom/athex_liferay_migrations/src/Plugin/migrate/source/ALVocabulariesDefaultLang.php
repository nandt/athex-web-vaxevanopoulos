<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrateSourceInterface;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\Vocabularies\VocabulariesIterator;


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
			// "companyId": 10154,
			// "createDate": 1381847330623,
			// "description": "< ? xml version='1.0' encoding='UTF-8' ? ><root available-locales=\"en_US\" default-locale=\"en_US\"><Description language-id=\"en_US\">Θεματική Κατάταξη Διαφόρων Εκδόσεων και Εγγράφων</Description></root>",
			// "descriptionCurrentValue": "Θεματική Κατάταξη Διαφόρων Εκδόσεων και Εγγράφων",
			// "groupId": 10180,
			// "modifiedDate": 1708163451361,
			// "name": "Publications & Other Documents Thematic Classification",
			// "settings": "multiValued=true\nselectedClassNameIds=0\n",
			// "title": "< ? xml version='1.0' encoding='UTF-8' ? ><root available-locales=\"en_US,el_GR,\" default-locale=\"en_US\"><Title language-id=\"en_US\">Publications &amp; Other Documents Thematic Classification</Title><Title language-id=\"el_GR\">Θεματική Κατάταξη Διαφόρων Εκδόσεων και Εγγράφων</Title></root>",
			// "titleCurrentValue": "Publications & Other Documents Thematic Classification",
			// "userId": 10407,
			// "userName": "Angelos Varvitsiotis",
			// "uuid": "d4bbcea2-c724-40ef-bb12-a7df4c27b254",
			// "vocabularyId": 1159471
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new VocabulariesIterator();
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
