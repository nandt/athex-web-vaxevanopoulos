<?php

namespace Drupal\athex_hermes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

use Drupal\athex_hermes\AthexModel\AddSubmissionFileRq;
use Drupal\athex_hermes\AthexModel\AddSubmissionRq;


/**
 * Class HermesApiController.
 */
class HermesApiController extends ControllerBase {

	public function addSubmission(Request $request) {
		$rq = new AddSubmissionRq($request);
		// ...
	}

	public function addSubmissionFile(Request $request) {
		$rq = new AddSubmissionFileRq($request);
		// ...
	}

}
