<?php

namespace Drupal\athex_inbroker_integration\Plugin\Block;

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
		return [
			'#theme' => 'athex_ticker_tape'
		];
	}
}
