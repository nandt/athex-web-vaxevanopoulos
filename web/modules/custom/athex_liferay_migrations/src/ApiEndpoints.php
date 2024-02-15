<?php

namespace Drupal\athex_liferay_migrations;

enum ApiEndpoints: string {
	case ASSETENTRY__GET_COUNT		= 'assetentry/get-company-entries-count';
	case ASSETENTRY__GET_ENTRIES	= 'assetentry/get-company-entries';
	case JOURNALARTICLE__GET_LATEST	= 'journalarticle/get-latest-article';
	case ASSETVOCAB__GET_COMPANY_VOCAB = 'assetvocabulary/get-company-vocabularies';
}
