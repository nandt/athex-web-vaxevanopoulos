<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use Drupal\athex_hermes\AthexData\LiferayEntity;


class LiferayAssetEntry extends LiferayEntity {

	public function __construct(array $rspData) {
		parent::__construct($rspData);
		$this->rectifyXmlAndLangsFromField('title');
	}

	/**
	 * Returns the article data in the specified language.
	 *
	 * @param int $langIdx
	 * @return array
	 */
	public function getData(int $langIdx) {
		$article = parent::getData($langIdx);

		if (!$article) return null;

		$article['title'] = $this->getI18nField('title', $langIdx);

		$article['createDate'] = round($article['createDate'] / 1000);
		$article['modifiedDate'] = round($article['modifiedDate'] / 1000);

		return $article;
	}

	/**
	 *
	 * @return array
	 */
	public function getNodeData(): array {
		$nodeData = [];
		foreach ($this->langs as $langIdx) {
			$node = $this->getData($langIdx);
			$nodeData[] = $node;
		}
		return $nodeData;
	}
}

/*
// Testing Code

require_once '../../../athex_hermes/src/AthexData/LiferayEntity.php';

$test = new LiferayAssetEntry(json_decode((
	true
	? "{
		\"classNameId\": 10108,
		\"classPK\": 1938847,
		\"classTypeId\": 0,
		\"classUuid\": \"6a4ae5e6-751e-4e7d-b03f-3cd8ed464ac0\",
		\"companyId\": 10154,
		\"createDate\": 1396606795142,
		\"description\": \"\",
		\"descriptionCurrentValue\": \"\",
		\"endDate\": null,
		\"entryId\": 1938854,
		\"expirationDate\": null,
		\"groupId\": 10180,
		\"height\": 0,
		\"layoutUuid\": \"\",
		\"mimeType\": \"text/html\",
		\"modifiedDate\": 1396606795238,
		\"priority\": 0.0,
		\"publishDate\": 1396606740000,
		\"startDate\": null,
		\"summary\": \"\",
		\"summaryCurrentValue\": \"\",
		\"title\": \"<?xml version='1.0' encoding='UTF-8'?><root available-locales=\\\"en_US,el_GR,\\\" default-locale=\\\"en_US\\\"><Title language-id=\\\"en_US\\\">Announcement 4098/2014 (no English translation available)</Title><Title language-id=\\\"el_GR\\\">ΓΝΩΣΤΟΠΟΙΗΣΗ ΑΛΛΑΓΗΣ ΣΥΝΘΕΣΗΣ ΔΙΟΙΚΗΤΙΚΟΥ ΣΥΜΒΟΥΛΙΟΥ</Title></root>\",
		\"titleCurrentValue\": \"ΓΝΩΣΤΟΠΟΙΗΣΗ ΑΛΛΑΓΗΣ ΣΥΝΘΕΣΗΣ ΔΙΟΙΚΗΤΙΚΟΥ ΣΥΜΒΟΥΛΙΟΥ\",
		\"url\": \"\",
		\"userId\": 10407,
		\"userName\": \"Angelos Varvitsiotis\",
		\"viewCount\": 28,
		\"visible\": true,
		\"width\": 0
	}"
	: "{
        \"classNameId\": 10010,
        \"classPK\": 729927,
        \"classTypeId\": 0,
        \"classUuid\": \"a844572b-509e-4ccd-b705-c4eba51139c1\",
        \"companyId\": 10154,
        \"createDate\": 1380521179411,
        \"description\": \"4ce48377-7768-49a9-adb6-e396480f8592\",
        \"descriptionCurrentValue\": \"4ce48377-7768-49a9-adb6-e396480f8592\",
        \"endDate\": null,
        \"entryId\": 729929,
        \"expirationDate\": null,
        \"groupId\": 10180,
        \"height\": 0,
        \"layoutUuid\": \"\",
        \"mimeType\": \"application/pdf\",
        \"modifiedDate\": 1395583137471,
        \"priority\": 0.0,
        \"publishDate\": null,
        \"startDate\": null,
        \"summary\": \"\",
        \"summaryCurrentValue\": \"\",
        \"title\": \"Οικονομική Έκθεση ΕΠΙΛΕΚΤΟΣ ΚΛΩΣ_ΡΓΙΑ  Α.Ε.Β.Ε. (2013,Ετήσιος Ισολογισμός,Μητρική-Ενοποιημένη).pdf\",
        \"titleCurrentValue\": \"Οικονομική Έκθεση ΕΠΙΛΕΚΤΟΣ ΚΛΩΣ_ΡΓΙΑ  Α.Ε.Β.Ε. (2013,Ετήσιος Ισολογισμός,Μητρική-Ενοποιημένη).pdf\",
        \"url\": \"\",
        \"userId\": 10407,
        \"userName\": \"Angelos Varvitsiotis\",
        \"viewCount\": 229,
        \"visible\": true,
        \"width\": 0
    }"
), true));

echo print_r(json_encode($test->getData(0)), true);
echo "\n";

/**/
