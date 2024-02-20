<?php

namespace Drupal\athex_liferay_migrations\Plugin\migrate\source;

use Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles\ArticleDataIterator;
use Drupal\athex_liferay_migrations\AthexLiferayIterator\Articles\ArticleDetailsIterator;


/**
 *
 * @MigrateSource(
 *   id = "athex_liferay_article_translations",
 *   source_module = "migrate"
 * )
 */
class ALArticleTranslationsSource extends ALArticlesDefaultLang {

	/**
	 * {@inheritdoc}
	 */
	public function initializeIterator() {
		return new ArticleDataIterator(new ArticleDetailsIterator(), true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString() {
		return 'Athex Liferay Article Translations';
	}
}
