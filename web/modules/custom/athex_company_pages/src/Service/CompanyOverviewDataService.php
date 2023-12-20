<?php

namespace Drupal\athex_company_pages\Service;

use Drupal\athex_sis_integration\Service\SisDbDataService;


class CompanyOverviewDataService {

	protected $sisdb;

	public function __construct(
		SisDbDataService $sisdb
	) {
		$this->sisdb = $sisdb;
	}

	public function getOverviewRows($company) {
		//TODO: implement
		return [];
	}

	public function getHistoricData($company) {
		//TODO: implement
		return [];
	}
}
