<?php

namespace Drupal\athex_hermes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Drupal\athex_hermes\AthexData\Submission;
use Drupal\athex_hermes\AthexData\SubmissionFile;
use Drupal\athex_hermes\Service\NodeUpdateService;

/**
 * Class HermesApiController.
 */
class HermesApiController extends ControllerBase {

	protected $nodes;

	public function __construct(
		NodeUpdateService $nodes
	) {
		$this->nodes = $nodes;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_hermes.node_update_service')
		);
	}

	public function addSubmission(Request $request) {
		$node = $this->nodes->update(new Submission($request));
		return new Response('', Response::HTTP_NO_CONTENT);
	}

	public function addSubmissionFile(Request $request) {
		$node = $this->nodes->update(new SubmissionFile($request));
		return new Response('', Response::HTTP_NO_CONTENT);
	}

}
