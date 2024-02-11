<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use DOMDocument;
use DOMElement;


class LiferayArticle {

	protected array $langs = [];
	protected array $data;

	private function getLangFromLocale(string $locale) {
		return explode('_', $locale)[0];
	}

	private function getLangFromNode(DOMElement $node, string $key) {
		return $this->getLangFromLocale($node->getAttribute($key));
	}

	private function getLangMap(DOMElement $xml) {
		$map = [];
		foreach ($xml->childNodes as $node) {
			if ($node instanceof DOMElement) {
				$l = $this->getLangFromNode($node, 'language-id');
				$map[$l] = $node->textContent;
			}
		}
		return $map;
	}

	private function rectifyXmlAndLangsFromField($key) {
		$xml = $this->data[$key];
		$dom = new DOMDocument();
		$dom->loadXML($xml);
		$root = $dom->documentElement;
		$this->data[$key] = $this->getLangMap($root);
		$contentLangs = array_keys($this->data[$key]);

		$fieldDfLang = $this->getLangFromNode($root, 'default-locale');

		//TODO:
		// 		keep only intersection of available languages from all fields
		// 		warn about removals
		// 		warn about articles where no intersection exists

		if (empty($this->langs)) {
			if (in_array($fieldDfLang, $contentLangs))
				$this->langs[] = $fieldDfLang;
			//TODO: else warn
		}
		else if ($fieldDfLang !== $this->langs[0]) {
			$resourcePrimKey = $this->data['resourcePrimKey'];
			throw new \Exception("Inconsistent default language for resourcePrimKey $resourcePrimKey");
		}

		foreach ($contentLangs as $l) {
			$l = $this->getLangFromLocale($l);
			if (!in_array($l, $this->langs)) {
				$this->langs[] = $l;
			}
		}
	}

	public function __construct(array $rspData) {
		$this->data = $rspData;
		$this->rectifyXmlAndLangsFromField('content');
		$this->rectifyXmlAndLangsFromField('title');
	}

	private function getI18nField(string $field, int $langIdx) {
		$lang = $this->langs[$langIdx];
		$trans = $this->data[$field];
		$trans = @$trans[$lang];
		if (!$trans)
			throw new \Exception(
				"No translation for language $lang in field $field for resourcePrimKey {$this->data['resourcePrimKey']}"
			);
		return $trans;
	}

	/**
	 * Returns the available languages for this article, of which the first is the default.
	 *
	 * @return array
	 */
	public function getLangs() {
		return $this->langs;
	}

	/**
	 * Returns the article data in the specified language.
	 *
	 * @param int $langIdx
	 * @return array
	 */
	public function getData(int $langIdx) {
		if ($langIdx >= count($this->langs)) return null;

		$article = $this->data;

		$article['content'] = $this->getI18nField('content', $langIdx);
		$article['title'] = $this->getI18nField('title', $langIdx);

		$article['createDate'] = $article['createDate'] / 1000;
		$article['displayDate'] = $article['displayDate'] / 1000;
		$article['modifiedDate'] = $article['modifiedDate'] / 1000;
		$article['statusDate'] = $article['statusDate'] / 1000;

		$article['langcode'] = $this->langs[$langIdx];
		$article['default_langcode'] = $this->langs[0];
		return $article;
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
