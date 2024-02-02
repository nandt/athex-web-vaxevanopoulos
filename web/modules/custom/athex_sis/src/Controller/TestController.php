<?php

namespace Drupal\athex_sis\Controller;

use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestController extends ControllerBase {

    protected $db;

    public function __construct(SisDbDataService $db) {
        $this->db = $db;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('athex_sis.db_data')
        );
    }

	public function test() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$resultsByIndex = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";

			$resultsByIndex[$gdValue] = $this->db->fetchAllWithParams($sql, [':gdValue' => $gdValue]);
		}

		// rest of your code


		// Generating table rows from the result
		$tableRows = [];
		foreach ($resultsByIndex as $symbol => $data) {
			$rowData = [$symbol]; // First column will be the symbol itself

			foreach ($data as $dataCell) {
				$rowData[] = $dataCell["TICKER_EN"]; // Change this according to your data structure
			}

			$tableRows[] = $rowData;
		}

		$table = [
			'#type' => 'table',
			'#rows' => $tableRows,
		];

		return $table; // Instead of a JsonResponse, you return the render array
	}


	public function fetchData() {
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];
		$resultsByIndex = [];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";

			$resultsByIndex[$gdValue] = $this->db->fetchAllWithParams($sql, [':gdValue' => $gdValue]);
		}

		return $resultsByIndex; // Return the raw data instead of a render array
	}





}


	// In your Block or Controller



