<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_sis\Service\SisDbDataService;

class StockDataService
{

	protected $sisdb;

	public function __construct(SisDbDataService $sisdb)
	{
		$this->sisdb = $sisdb;
	}

	public function search(array $filters, int $offset, int $limit)
	{
		$sql = <<<SQL
    SELECT
        s.TICKER_EN AS "Symbol",
        s.ISIN AS "ISIN",
        c.NAME_EN AS "Issuer",
        CASE
            WHEN m.SIS_CODE = 30117 THEN 'SECURITIES MARKET'
            WHEN m.SIS_CODE = 30250 THEN 'ALTERNATIVE MARKET'
            ELSE 'Other'
        END AS "Market",
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
        (s.ISIN LIKE :searchValue OR s.TICKER_EN LIKE :searchValue OR s.NAME_EN LIKE :searchValue)
        AND (:marketFilter IS NULL OR m.SIS_CODE = :marketFilter)
        AND (:minPrice IS NULL OR h.close_value >= :minPrice)
        AND (:maxPrice IS NULL OR h.close_value <= :maxPrice)
        AND (:letterFilter IS NULL OR s.TICKER_EN LIKE :letterFilter) -- Filter by the first letter of the symbol
    SQL;

		$params = [
			':searchValue' => '%' . ($filters['searchValue'] ?? '') . '%',
			':marketFilter' => $filters['market'] ?? null,
			':minPrice' => $filters['minPrice'] ?? null,
			':maxPrice' => $filters['maxPrice'] ?? null,
			':letterFilter' => $filters['letterFilter'] ? $filters['letterFilter'] . '%' : null, // Add the letter filter
		];

		$result = $this->sisdb->fetchAllWithParams($sql, $params);
		return $result ?: [];
	}

}
