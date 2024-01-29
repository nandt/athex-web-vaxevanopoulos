<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;


/**
 * Provides an "Market Highlights" block.
 *
 * @Block(
 * 	id = "market_highlights",
 * 	admin_label = @Translation("Market Highlights"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class MarketHighlightsBlock extends BlockBase implements BlockPluginInterface {
	private function dummyReplicate($data) {
		$result = [];
		for ($i = 0; $i < 5; $i++) {
			$result[] = $data;
		}
		return $result;
	}

	public function build() {
		return [
			'#theme' => 'market_highlights',
			'#sections' => [
				[
					'en_name' => 'Top Risers',
					'items' => $this->dummyReplicate([
						'company' => 'Intracom',
						'symbol' => 'INTRA',
						'value' => 874.50,
						'change' => 20.1
					])
				], [
					'en_name' => 'Top Fallers',
					'items' => $this->dummyReplicate([
						'company' => 'Intracom',
						'symbol' => 'INTRA',
						'value' => 874.50,
						'change' => -20.1
					])
				], [
					'en_name' => 'Most Active',
					'items' => $this->dummyReplicate([
						'company' => 'Intracom',
						'symbol' => 'INTRA',
						'value' => 874.50,
						'change' => 20.1
					])
				]
			]
		];
	}
}
