<?php

namespace Drupal\athex_hermes\AthexData;

use DOMDocument;
use DOMElement;


class LiferayEntity {

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

	protected function rectifyXmlAndLangsFromField($key) {
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
		// 		warn about entities where no intersection exists

		if (empty($this->langs)) {
			if (in_array($fieldDfLang, $contentLangs))
				$this->langs[] = $fieldDfLang;
			//TODO: else warn
		}
		else if ($fieldDfLang !== $this->langs[0]) {
			throw new \Exception("Inconsistent default language");
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
	}

	protected function getI18nField(string $field, int $langIdx) {
		$lang = $this->langs[$langIdx];
		$trans = $this->data[$field];
		$trans = @$trans[$lang];
		if (!$trans)
			throw new \Exception(
				"No translation for language $lang in field $field"
			);
		return $trans;
	}

	/**
	 * Returns the available languages for this entity, of which the first is the default.
	 *
	 * @return array
	 */
	public function getLangs() {
		return $this->langs;
	}

	/**
	 * Returns the entity data in the specified language.
	 *
	 * @param int $langIdx
	 * @return array
	 */
	public function getData(int $langIdx) {
		if ($langIdx >= count($this->langs)) return null;

		$entity = $this->data;
		$entity['langcode'] = $this->langs[$langIdx];
		$entity['default_langcode'] = $this->langs[0];

		return $entity;
	}
}
