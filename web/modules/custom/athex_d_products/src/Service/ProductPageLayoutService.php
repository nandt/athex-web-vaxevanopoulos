<?php

namespace Drupal\athex_d_products\Service;

use Drupal\athex_d_mde\AthexRendering\BsNavLinks;
use Drupal\athex_d_products\ProductType;
use Drupal\Core\Url;

class ProductPageLayoutService {

	public function render(
		ProductType $product_type,
		string $product_id,
		$content = []
	) {
		$params = [
			'product_type' => $product_type->value,
			'product_id' => $product_id
		];
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
			(new BsNavLinks([
				'Profile' => Url::fromRoute('athex_d_products.stock_profile', $params),
				'Issuer' => Url::fromRoute('athex_d_products.product_issuer', $params),
				'Financial Data & Announcements' => Url::fromRoute('athex_d_products.product_publications', $params)
			]))->render()
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
