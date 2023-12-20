<?php

namespace Drupal\athex_sis_integration\Controller;

use Drupal\athex_sis_integration\Service\SisDbDataService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestController extends ControllerBase {

	protected $db;

	public function __construct(
		SisDbDataService $db
	) {
		$this->db = $db;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_sis.db_data')
		);
	}

	public function test() {
		$rc = -1;
		$res = $this->db->fetchAll('SELECT * FROM HELEX_BLOCKS', 0, 10, $rc);
		return new JsonResponse([
			'rc' => $rc,
			'res' => $res
		]);
	}
}
