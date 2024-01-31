<?php

namespace Drupal\athex_sis\Controller;

use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestController extends ControllerBase
{

	protected $db;

	public function __construct(SisDbDataService $db)
	{
		$this->db = $db;
	}

	public static function create(ContainerInterface $container)
	{
		return new static(
			$container->get('athex_sis.db_data')
		);
	}

	public function test()
	{
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE']; // Array of values to query
		$resultsByIndex = [];  // Array to store results for each index

		foreach ($gdValues as $gdValue) {
			// SQL query for each value in the array
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                    JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                    JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                    WHERE hi.TICKER_EN = '$gdValue'";

			// Fetch results for the current value
			$result = $this->db->fetchAll($sql);

			// Store results in associative array under the index symbol
			$resultsByIndex[$gdValue] = $result;
		}

		// Prepare and return response with results organized by index symbol
		return new JsonResponse(['data' => $resultsByIndex]);
	}
}
