<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a "Ticker Tape" block.
 *
 * @Block(
 * 	id = "ticker_tape",
 * 	admin_label = @Translation("Ticker Tape"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class TickerTapeBlock extends BlockBase implements BlockPluginInterface {

	public function build() {
		$service = \Drupal::service('athex_d_mde.ticker_tape');

		$result = [
			'#theme' => 'ticker_tape',
			// '#attached' => [
			// 	'library' => ['athex_d_mde/ticker_tape']
			// ],
			'#info' => [
				'#type' => 'container',
				'#attributes' => [
					'class' => ['ticker-info']
				],
				$service->getPrimaryInfoRenderArray()
			],
			'#items' => [
				'#type' => 'container',
				'#attributes' => [
					'class' => ['tickertapeWrapper']
				],
				// '#attributes' => [
				// 	'class' => ['ticker-item-template'],
				// 	'style' => 'display: none;'
				// ],
				...$service->getTapeItemRenderArray()
				// 'template' => [
				// 	'#theme' => 'ticker_tape_item'
				// ]
			],
		];

		// $item_vars = athex_d_mde_integration_theme()['ticker_tape_item']['variables'];

		// foreach ($item_vars as $var => $default)
		// 	$result['#items']['template']['#' . $var] = [
		// 		'#markup' => "<span data-placeholder=\"$var\"></span>"
		// 	];

		return $result;
	}
}
