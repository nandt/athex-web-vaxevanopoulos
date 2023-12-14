<?php

namespace Drupal\athex_company_pages\Service;


class CompanyPageLayoutService {

	public function render($company_id, $content = []) {
		return array_merge([
			[
				'#theme' => 'company_hero',
				'#company_name' => [
					'#type' => 'html_tag',
					'#tag' => 'h1',
					'#value' => 'ΑΘΗΝΑΪΚΟΣ ΧΡΗΜΑΤΙΣΤΗΡΙΑΚΟΣ ΟΡΓΑΝΙΣΜΟΣ'
				],
				'#cover_img_url' => null,
				'#logo_url' => null,
				'#ticker' => [
					'#theme' => 'company_ticker'
				]
			], [
				// '#type' => 'contextual_links'
				//TODO: nav tabs // contextual_links // local_tasks ??
			]
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
