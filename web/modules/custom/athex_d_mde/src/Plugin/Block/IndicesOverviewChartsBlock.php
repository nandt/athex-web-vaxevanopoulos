<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides an "Indices Overview Charts" block.
 *
 * @Block(
 * 	id = "indices_overview_charts",
 * 	admin_label = @Translation("Indices Overview Charts"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class IndicesOverviewChartsBlock extends BlockBase implements BlockPluginInterface {

	public function build() {
		$service = \Drupal::service('athex_d_mde.indices_overview_tables');
		return $service->getBlockRA();
	}
}
