<?php

namespace Drupal\athex_hermes\AthexData;

use Symfony\Component\HttpFoundation\Request;

use Drupal\athex_hermes\AthexModel\AddSubmissionRq;


class Submission extends LiferayEntity implements ProvidesNodeData {

	public function __construct(Request $request) {
		$rqobj = new AddSubmissionRq($request);
		parent::__construct(get_object_vars($rqobj));
		$this->rectifyXmlAndLangsFromField('titleMapValues');
		$this->rectifyXmlAndLangsFromField('content');
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

		$submission['titleMapValues'] = $this->getI18nField('titleMapValues', $langIdx);
		$submission['content'] = $this->getI18nField('content', $langIdx);

		$submission['displayDateTimestamp'] = $submission['displayDateTimestamp'] / 1000;

		return $submission;
	}

	/**
	 *
	 * @return array
	 */
	public function getNodeData(): array {
		$nodeData = [];
		foreach ($this->langs as $langIdx) {
			$node = $this->getData($langIdx);
			$node['content'] = [
				'value' => $node['content'],
				'format' => 'full_html'
			];

			$nodeData[] = $node;
		}
		return $nodeData;
	}

}
