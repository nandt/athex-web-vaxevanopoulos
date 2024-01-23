<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_sis\Service\SisDbDataService;


class StockDataService {

	protected $sisdb;

	public function __construct(
		SisDbDataService $sisdb
	) {
		$this->sisdb = $sisdb;
	}

	public function search(array $filters, int $offset, int $limit) {

		//TODO: Get real data

		$result = [];
		for ($i = 0; $i < 10; $i++) {
			$result[] = [
				'symbol' => 'ATG 10010',
				'company' => 'ABN AMRO BANK N.V.',
				'isin' => 'NL0000852564',
				'market' => 'ALTERNATIVE',
				'last' => 'EUR 29.33',
				'percent' => '3.56%',
				'date_time' => '25/10/2023 10:28 CEST'
			];
		}
		return $result;
	}
}
