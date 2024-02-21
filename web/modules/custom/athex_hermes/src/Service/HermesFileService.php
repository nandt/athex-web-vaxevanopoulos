<?php

namespace Drupal\athex_hermes\Service;

use Drupal\Core\File\FileSystemInterface;
use Drupal\file\FileRepositoryInterface;


class HermesFileService {

	protected $fileSys;
	protected $fileRepo;

	public function __construct(
		FileSystemInterface $fileSys,
		FileRepositoryInterface $fileRepo
	) {
		$this->fileSys = $fileSys;
		$this->fileRepo = $fileRepo;
  	}

	public function store(
		string $alfrescoUuid,
		string $fileName,
		string $contents
	) {
		$path = join('/', [
			'public://hermes',
			date('Y-m'),
			$alfrescoUuid
		]);
		$this->fileSys->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY);
		return $this->fileRepo->writeData(
			$contents,
			"$path/$fileName",
			FileSystemInterface::EXISTS_ERROR
		);
	}
}
