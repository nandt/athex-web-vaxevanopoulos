<?php

namespace Drupal\athex_hermes\AthexModel;

use DOMElement;


abstract class HermesRequest {

	private \DOMXPath $xpath;

	private function getRefVal(DOMElement $element) {
		$attr = $element->getAttribute('href');
		if ($attr[0] !== '#') return null;
		$attr = substr($attr, 1);
		$ref = @$this->xpath->query("//multiRef[@id='$attr']")[0];
		return $this->getVal($ref);
	}

	private function getVal(DOMElement $element) {
		if (@$element->hasAttribute('href'))
			return $this->getRefVal($element);

		$val = null;
		foreach ($element->childNodes as $k => $v) {
			if (!@$v->tagName) {
				if (!$k)
					$val = trim($v->textContent);
				continue;
			}

			if (!is_array($val))
				$val = [];

			if ($v->tagName === $element->tagName)
				$val[] = $this->getVal($v);
			else
				$val[$v->tagName] = $this->getVal($v);
		}
		return $val;
	}

	public function __construct(DOMElement $node, \DOMXPath $xpath) {
		$this->xpath = $xpath;
		foreach ($node->childNodes as $prop) {
			if (!@$prop->tagName) continue;
			$this->{$prop->tagName} = $this->getVal($prop);
		}
	}
}
