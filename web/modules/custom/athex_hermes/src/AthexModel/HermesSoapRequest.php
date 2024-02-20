<?php

namespace Drupal\athex_hermes\AthexModel;

use Symfony\Component\HttpFoundation\Request;

use Drupal\athex_hermes\AthexData\Submission;
use Drupal\athex_hermes\AthexData\SubmissionFile;


class HermesSoapRequest {

	public readonly array $submissions;

	public function __construct(Request $rq) {
		$doc = new \DOMDocument();
		$doc->loadXML($rq->getContent());
		$xpath = new \DOMXPath($doc);
		$res = @$xpath->query("//soapenv:Body")[0];
		if (empty($res)) return;
		$res = $res->childNodes;
		$submissions = [];
		foreach ($res as $r) switch (@$r->tagName) {
			case 'ns1:addHermesSubmission':
				$submissions[] = new Submission($r, $xpath);
				break;
			case 'ns1:addHermesSubmissionAsFile':
				$submissions[] = new SubmissionFile($r, $xpath);
		}
		$this->submissions = $submissions;
	}
}
