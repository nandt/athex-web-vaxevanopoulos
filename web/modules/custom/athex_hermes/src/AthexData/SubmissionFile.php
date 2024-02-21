<?php

namespace Drupal\athex_hermes\AthexData;

use Drupal\athex_hermes\AthexModel\AddSubmissionFileRq;
use Drupal\athex_hermes\Service\HermesFileService;


class SubmissionFile extends SubmissionNodeData {

	private HermesFileService $fileSvc;

	protected array $data;


	private function base64ToFile(string $key, string $fileName) {
		if (empty(@$this->data[$key])) return;

		$file = $this->fileSvc->store(
			$this->data['alfrescoUUID'],
			$fileName,
			base64_decode($this->data[$key])
		);

		$this->data['files'][] = $file;
	}

	public function __construct(\DOMElement $node, \DOMXPath $xpath) {
		$this->fileSvc = \Drupal::service('athex_hermes.hermes_files');
		$obj = new AddSubmissionFileRq($node, $xpath);
		$d = $this->data = get_object_vars($obj);
		$d = $d['title'];
		$this->base64ToFile('contents', $d);
		$this->base64ToFile('attachmentsAsZip', "$d.zip");
	}

	public function getNodeData(): array {
		return [$this->data];
	}

}
