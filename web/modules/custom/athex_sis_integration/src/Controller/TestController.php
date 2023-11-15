<?php

namespace Drupal\athex_sis_integration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestController extends ControllerBase {
	public function __construct() {}

	public static function create(ContainerInterface $container) {
		return new static();
	}

	public function test() {
		$c = oci_connect(
			"",	// username
			"",	// password
			""	// connection_string
		);

		$cmd = oci_parse($c, 'SELECT * FROM HELEX_BLOCKS');
		oci_execute($cmd);

		$res = [];

		$rc = oci_fetch_all($cmd, $res, 0, 10);

		return new JsonResponse([
			'rc' => $rc,
			'res' => $res
		]);
	}
}
