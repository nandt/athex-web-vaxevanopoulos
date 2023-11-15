<?php

namespace Drupal\athex_inbroker_integration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;

class TestController extends ControllerBase {
	public function __construct(
		ClientInterface $http
	) {
		$this->http = $http;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('http_client')
		);
	}

	//TODO: move to service
	private function callRealtime($transaction, $args = []) {
		$realtimeDataHost = '';
		$args += [
			'userName' => '',
			'company' => '',
			'IBSessionId' => '',
			'lang' => 'GR',
			'format' => 'json'
		];
		$args = http_build_query($args);
		$get = $this->http->get(
			$realtimeDataHost . '/' . $transaction . '?' . $args
		);
		return json_decode($get->getBody()->getContents(), true);
	}

	public function test() {
		return new JsonResponse([
			'res' => $this->callRealtime('Info', ['code' => 'ETE.ATH'])
		]);
	}
}
