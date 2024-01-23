<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_products\ProductType;


class ProductPageLayoutService {

	public function render(
		ProductType $product_type,
		string $product_id,
		$content = []
	) {
		return array_merge([
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
			new BsNav(
				['Profile', 'Issuer', 'Financial Data & Announcements']
			)
		], $content);
	}

	public function h2($text) {
		return [
			'#type' => 'html_tag',
			'#tag' => 'h2',
			'#value' => t($text)
		];
	}
}
