<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;


/**
 * Provides an "Financial Calendar" block.
 *
 * @Block(
 * 	id = "financial_calendar",
 * 	admin_label = @Translation("Financial Calendar"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class FinancialCalendarBlock extends BlockBase implements BlockPluginInterface {

	public function build() {
		return [
			'#markup' => '
				<h2>Financial Calendar</h2>
			'
		];
	}
}
