<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use Drupal\athex_hermes\AthexData\LiferayEntity;
use Drupal\athex_hermes\AthexData\SubmissionNodeData;


class LiferayArticle extends LiferayEntity implements SubmissionNodeData {

	public function __construct(array $rspData) {
		parent::__construct($rspData);
		$this->rectifyXmlAndLangsFromField('content');
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

		$article['content'] = $this->getI18nField('content', $langIdx);
		$article['title'] = $this->getI18nField('title', $langIdx);

		$article['createDate'] = $article['createDate'] / 1000;
		$article['displayDate'] = $article['displayDate'] / 1000;
		$article['modifiedDate'] = $article['modifiedDate'] / 1000;
		$article['statusDate'] = $article['statusDate'] / 1000;

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
			$node['content'] = [
				'value' => $node['content'],
				'format' => 'full_html'
			];

			$nodeData[] = $node;
		}
		return $nodeData;
	}
}

/*
// Testing Code

$test = new LiferayArticle(json_decode("{
    \"articleId\": \"1939186\",
    \"classNameId\": 0,
    \"classPK\": 0,
    \"companyId\": 10154,
    \"content\": \"<?xml version=\\\"1.0\\\"?>\\n\\n<root available-locales=\\\"en_US,el_GR\\\" default-locale=\\\"en_US\\\">\\n\\t<static-content language-id=\\\"en_US\\\"><![CDATA[<p style=\\\"text-align: justify;\\\" align=\\\"right\\\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Peania, April 3, 2014</p>\\n<p style=\\\"text-align: justify;\\\">In reply to the letter of the Hellenic Capital Market Commission, (protocol number 1188) regarding the today's article of the newspaper \\\"H KATHIMERINI&rdquo; entitled \\\"Ιt is a matter of time the acquisition of Hellas Online by Vodafone which seems to have reached an agreement with Intracom Holdings, which controls the 57% stake, for the agreement's price&rdquo; and according to article 2, Decision 5/204/2000 of the Hellenic Capital Market Commission's Bod, as valid, Intracom Holdings, as Hellas Online's shareholder, controlling the 57.24%, and Vodafone-Panafon, also as Hellas Online's shareholder, which holds a stake of 18.43%, both do confirm that the two Groups-shareholders of Hellas Online are still discussing considering all options and forms of cooperation.</p>\\n<p style=\\\"text-align: justify;\\\">Intracom Holdings will timely inform should new developments arise on the said issue.</p>]]></static-content>\\n\\t<static-content language-id=\\\"el_GR\\\"><![CDATA[Αυτή η Ανακοίνωση είναι διαθέσιμη μόνο στα <a href=\\\"javascript:void(0)\\\">Αγγλικά</a>.]]></static-content>\\n</root>\",
    \"createDate\": 1396615889106,
    \"description\": \"\",
    \"descriptionCurrentValue\": \"\",
    \"displayDate\": 1396615860000,
    \"expirationDate\": null,
    \"groupId\": 10180,
    \"id\": 1939187,
    \"indexable\": true,
    \"layoutUuid\": \"\",
    \"modifiedDate\": 1396615889260,
    \"resourcePrimKey\": 1939188,
    \"reviewDate\": null,
    \"smallImage\": false,
    \"smallImageId\": 0,
    \"smallImageURL\": \"\",
    \"status\": 0,
    \"statusByUserId\": 1921523,
    \"statusByUserName\": \"Liana Petrogona\",
    \"statusDate\": 1396615889260,
    \"structureId\": \"\",
    \"templateId\": \"\",
    \"title\": \"<?xml version='1.0' encoding='UTF-8'?><root available-locales=\\\"en_US,el_GR,\\\" default-locale=\\\"en_US\\\"><Title language-id=\\\"en_US\\\">Reply to the letter of the Hellenic Capital Market Commission</Title><Title language-id=\\\"el_GR\\\">Ανακοίνωση 4109/2014 (δεν είναι διαθέσιμη η Ελληνική μετάφραση)</Title></root>\",
    \"titleCurrentValue\": \"Reply to the letter of the Hellenic Capital Market Commission\",
    \"type\": \"announcements\",
    \"urlTitle\": \"reply-to-the-letter-of-the-hellenic-capital-market-commissi-2\",
    \"userId\": 10407,
    \"userName\": \"Angelos Varvitsiotis\",
    \"uuid\": \"9106a60a-7f65-4f5d-a25b-c5f9d4273a68\",
    \"version\": 1.0
}", true));

echo print_r($test->getData(1), true);
echo "\n";

/**/
