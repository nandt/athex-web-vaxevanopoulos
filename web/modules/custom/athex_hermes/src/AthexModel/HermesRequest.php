<?php

namespace Drupal\athex_hermes\AthexModel;

use Symfony\Component\HttpFoundation\Request;


abstract class HermesRequest {

	// private function fromXml(string $xml) {
	// 	$doc = new \DOMDocument();
	// 	$doc->loadXML($xml);
	// 	$xpath = new \DOMXPath($doc);
	// 	$properties = get_object_vars($this);

	// 	foreach ($properties as $property => $value) {
	// 		$nodes = $xpath->query("//$property");
	// 		if ($nodes->length > 0) {
	// 			$this->{$property} = $nodes->item(0)->nodeValue;
	// 		}
	// 	}
	// }

	private function fromJson(string $json) {
		$data = json_decode($json, true);
		$properties = get_class_vars(get_class($this));
		foreach ($properties as $property => $value) {
			if (!isset($this->{$property})) {
				$this->{$property} = @$data[$property];
			}
		}
	}

	public function __construct(Request $request) {
		$this->fromJson($request->getContent());
	}
}
