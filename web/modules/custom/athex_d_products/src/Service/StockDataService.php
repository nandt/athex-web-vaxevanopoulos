<?php
/*
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
*/

namespace Drupal\athex_d_products\Service;

use Drupal\athex_sis\Service\SisDbDataService;

class StockDataService {

	protected $sisdb;

	public function __construct(SisDbDataService $sisdb) {
		$this->sisdb = $sisdb;
	}

	public function search(array $filters, int $offset, int $limit) {
		$sql = <<<SQL
SELECT
    s.TICKER_EN AS "Symbol",
    s.ISIN AS "ISIN",
    c.NAME_EN AS "Issuer",
    m.NAME_EN AS "Market",
    h.close_value AS "Last Price",
    h.trading_date AS "Last Trading Date",
    h.change_percentage AS "Percentage"
FROM
    NS_STOCKS s
JOIN
    NS_MARKETS m ON s.MARKET_ID = m.SIS_CODE
JOIN
    ns_companies c ON s.COMPANY_ID = c.SIS_CODE
LEFT JOIN (
    SELECT
        stock_id,
        close_value,
        trading_date,
        change_percentage,
        ROW_NUMBER() OVER (PARTITION BY stock_id ORDER BY trading_date DESC) AS rn
    FROM
        HELEX_STOCKS_HISTORY
) h ON s.SIS_CODE = h.stock_id AND h.rn = 1
WHERE
    s.ISIN LIKE :searchValue OR
    s.TICKER_EN LIKE :searchValue OR
    s.NAME_EN LIKE :searchValue
SQL;



		// Prepare search value with wildcards for LIKE operator
		$searchValue = '%' . $filters['searchValue'] . '%';

		// Execute the query with parameters
		$result = $this->sisdb->fetchAllWithParams($sql, [
			':searchValue' => $searchValue
		]);
		//var_dump($result);
		// Process results if needed or return directly
		return $result;
	}
}
