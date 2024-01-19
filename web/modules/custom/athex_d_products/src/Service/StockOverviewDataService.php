<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_sis\Service\SisDbDataService;


class StockOverviewDataService {

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
