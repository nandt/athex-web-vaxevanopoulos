<?php

namespace Drupal\athex_hermes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Drupal\athex_hermes\AthexModel\HermesSoapRequest;
use Drupal\athex_hermes\Service\HermesFileService;
use Drupal\athex_hermes\Service\NodeUpdateService;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;


/**
 * Class HermesApiController.
 */
class HermesApiController extends ControllerBase {

	private $logger;
	private $nodes;
	private $files;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		NodeUpdateService $nodes,
		HermesFileService $files
	) {
		$this->logger = $loggerFactory->get('athex_hermes');
		$this->nodes = $nodes;
		$this->files = $files;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('logger.factory'),
			$container->get('athex_hermes.node_update_service'),
			$container->get('athex_hermes.hermes_files')
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

		$http = '';
		$headers = apache_request_headers();
		foreach ($headers as $header => $value) {
			$http .= "$header: $value\n";
		}
		$http .= "\n";
		$http .= $request->getContent();

		foreach ($rq->submissions as $sub) {
			$node = $this->nodes->alfrescoUpdate($sub);
			$this->files->store(
				$node->field_alfrescouuid->value,
				'__request',
				$http
			);
			$this->logger->info(
				"Processed submission with alfrescoUUID @uuid",
				[
					'@uuid' => $node->field_alfrescouuid->value,
					'link' => $node->toLink($this->t('View'))->toString()
				]
			);
		}

		return new Response('', Response::HTTP_NO_CONTENT);
	}

}
