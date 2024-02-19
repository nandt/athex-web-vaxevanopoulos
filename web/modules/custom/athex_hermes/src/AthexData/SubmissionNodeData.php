<?php

namespace Drupal\athex_hermes\AthexData;


abstract class SubmissionNodeData {
	public abstract function getNodeData(): array;

	public function getFinalNodeData(): array {
		//TODO: check if anything needs to be done with properties:
		//  vocabularies, categories, properties, tagNames
		return $this->getNodeData();
	}
}
