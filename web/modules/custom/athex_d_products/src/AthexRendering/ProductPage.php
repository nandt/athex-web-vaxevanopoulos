<?php

namespace Drupal\athex_d_products\AthexRendering;

use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;

use Drupal\athex_d_mde\AthexRendering\BsNavLinks;


class ProductPage {

	use StringTranslationTrait;

	private Array $renderArray;
	private ?Array $currRow = [];

	public function __construct($data) {
		$params = [
			'product_type' => $data['product_type'],
			'product_id' => $data['product_id']
		];
		$this->renderArray = [
			[
				'#theme' => 'product_hero',
				'#product_name' => [
					'#type' => 'html_tag',
					'#tag' => 'h1',
					'#value' => 'ΑΘΗΝΑΪΚΟΣ ΧΡΗΜΑΤΙΣΤΗΡΙΑΚΟΣ ΟΡΓΑΝΙΣΜΟΣ'
				],
				'#cover_img_url' => null,
				'#logo_url' => null,
				'#ticker' => [
					'#theme' => 'product_ticker'
				]
			],
			(new BsNavLinks([
				'Profile' => Url::fromRoute('athex_d_products.product_profile', $params),
				'Issuer' => Url::fromRoute('athex_d_products.product_issuer', $params),
				'Financial Data & Announcements' => Url::fromRoute('athex_d_products.product_publications', $params)
			]))->render()
		];
	}

	public function h2($text) {
		return [
			'#type' => 'html_tag',
			'#tag' => 'h2',
			'#value' => $this->t($text)
		];
	}

	private function closeRow() {
		if (!count($this->currRow)) return;

		$this->renderArray[] = BsGrid::renderContainer([
			BsGrid::renderRow(
				$this->currRow
			)
		]);

		$this->currRow = [];
	}

	public function addContent($content) {
		$this->closeRow();
		$this->renderArray[] = $content;
	}

	public function addCol($enTitle, $content) {
		if (count($this->currRow) > 1)
			$this->closeRow();

		$row = [];

		if ($enTitle)
			$row[] = [
				'#type' => 'html_tag',
				'#tag' => 'h2',
				'#value' => $this->t(
					$enTitle
				)
			];

		$row[] = $content;

		$this->currRow[] = $row;
	}

	public function render() {
		$this->closeRow();
		return $this->renderArray;
	}
}
