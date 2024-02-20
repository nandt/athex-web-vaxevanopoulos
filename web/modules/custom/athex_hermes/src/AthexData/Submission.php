<?php

namespace Drupal\athex_hermes\AthexData;

use Drupal\athex_hermes\AthexModel\AddSubmissionRq;
use Drupal\athex_hermes\Service\HermesFileService;


class Submission extends LiferayEntity {

	private HermesFileService $fileSvc;

	private array $files = [];


	private function getFiles(): array {
		$d = $this->data;
		$results = [];
		foreach ($d['fileURLs'] as $i => $url) {
			$fileName = explode('/', $url);
			$fileName = $d['fileNames'][$i] ?: end($fileName);
			$results[] = $this->fileSvc->store(
				$this->data['alfrescoUUID'],
				$fileName,
				file_get_contents($url)
			);
		}
		return $results;
	}

	public function __construct(\DOMElement $node, \DOMXPath $xpath) {
		$this->fileSvc = \Drupal::service('athex_hermes.hermes_files');
		$rqobj = new AddSubmissionRq($node, $xpath);
		$rqobj = get_object_vars($rqobj);
		parent::__construct($rqobj);
		// $this->rectifyXmlAndLangsFromField('titleMapValues');
		$this->rectifyXmlAndLangsFromField('content');
		$this->files = $this->getFiles();
	}

	/**
	 * Returns the submission data in the specified language.
	 *
	 * @param int $langIdx
	 * @return array
	 */
	public function getData(int $langIdx) {
		$submission = parent::getData($langIdx);

		if (!$submission) return null;

		// $submission['titleMapValues'] = $this->getI18nField('titleMapValues', $langIdx);
		$submission['content'] = $this->getI18nField('content', $langIdx);

		$submission['displayDateTimestamp'] = round($submission['displayDateTimestamp'] / 1000);

		return $submission;
	}

	/**
	 *
	 * @return array
	 */
	public function getNodeData(): array {
		$nodeData = [];
		foreach ($this->langs as $langIdx => $lang) {
			$node = $this->getData($langIdx);
			$node['content'] = [
				'value' => $node['content'],
				'format' => 'full_html'
			];
			$node['files'] = $this->files;

			$nodeData[] = $node;
		}
		return $nodeData;
	}

}
