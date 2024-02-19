<?php

namespace Drupal\athex_hermes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Drupal\athex_hermes\AthexModel\HermesSoapRequest;
use Drupal\athex_hermes\Service\NodeUpdateService;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class HermesApiController.
 */
class HermesApiController extends ControllerBase {

	private $logger;
	private $nodes;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		NodeUpdateService $nodes
	) {
		$this->logger = $loggerFactory->get('athex_hermes');
		$this->nodes = $nodes;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('logger.factory'),
			$container->get('athex_hermes.node_update_service')
		);
	}

	public function processPost(Request $request) {
		$rq = null;
		try {
			$rq = new HermesSoapRequest($request);
		}
		catch (\Throwable $ex) {
			$this->logger->error(
				"Received Bad Request\n"
				. $ex->__toString()
			);
			return new Response('BAD_REQUEST', Response::HTTP_BAD_REQUEST);
		}

		//TODO: pass request to save contents in associated file

		foreach ($rq->submissions as $sub)
			$this->nodes->alfrescoUpdate($sub);

		return new Response('OK', Response::HTTP_NO_CONTENT);
	}

}
