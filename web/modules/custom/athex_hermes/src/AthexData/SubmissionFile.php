<?php

namespace Drupal\athex_hermes\AthexData;

use Symfony\Component\HttpFoundation\Request;

use Drupal\athex_hermes\AthexModel\AddSubmissionFileRq;
use Drupal\Core\File\FileSystemInterface;

class SubmissionFile implements SubmissionNodeData {

	protected array $data;

	private function base64ToFile(string $key) {
		if (empty(@$this->data[$key])) return;

		$path = join('/', [
			'public://hermes',
			$this->data['alfrescoUUID'],
			$this->data['title']
		]);
		$contents = base64_decode($this->data[$key]);
		/** @var \Drupal\file\FileRepositoryInterface $fileRepository */
		$fileRepository = \Drupal::service('file.repository');
		$file = $fileRepository->writeData(
			$contents, $path,
			FileSystemInterface::EXISTS_ERROR
		);

		$this->data['files'][] = $file;
	}

	public function __construct(Request $request) {
		$obj = new AddSubmissionFileRq($request);
		$this->data = get_object_vars($obj);
		$this->base64ToFile('contents');
		// $this->base64ToFile('attachmentsAsZip');
	}

	public function getNodeData(): array {
		return [$this->data];
	}

}
