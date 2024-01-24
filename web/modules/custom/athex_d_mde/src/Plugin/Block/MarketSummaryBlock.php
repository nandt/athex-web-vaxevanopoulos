<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\athex_d_mde\AthexRendering\ProductsTable;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

use Drupal\athex_d_mde\AthexRendering\TabbedContainer;


/**
 * Provides an "Market Summary" block.
 *
 * @Block(
 * 	id = "market_summary",
 * 	admin_label = @Translation("Market Summary"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class MarketSummaryBlock extends BlockBase implements BlockPluginInterface {
	private function dummyReplicate($data) {
		$result = [];
		for ($i = 0; $i < 6; $i++) {
			$result[] = $data;
		}
		return $result;
	}

	public function build() {
		$summary = (new TabbedContainer(
			['Main', 'Bond Indices', 'Sector', 'Other', 'Total Return']
		));


		$activity = (new TabbedContainer(
			['Stocks', 'Derivatives', 'Bonds', 'ETF', 'ESMA']
		));

		return [
			'#theme' => 'market_summary',
			'#indices_overview' => [
				$summary->render(
					(new ProductsTable(
						$this->dummyReplicate([
							'GD', 7402.14, '+97.39', -1.3
						])
					))->render()
				),
				[
					'#type' => 'link',
					'#title' => $this->t('Explore Indices'),
					'#url' => \Drupal\Core\Url::fromUri('internal:#'),
					'#attributes' => [
						'class' => ['md-link']
					]
				]
			],
			'#market_activity' => [
				$activity->render(
					(new ProductsTable(
						$this->dummyReplicate([
							'ATG', '[chart]', 695.55, '+85.9', -7.39
						])
					))->render()
				),
				[
					'#type' => 'link',
					'#title' => $this->t('Explore Stocks'),
					'#url' => \Drupal\Core\Url::fromUri('internal:#'),
					'#attributes' => [
						'class' => ['md-link']
					]
				]
			]
		];
	}
}
