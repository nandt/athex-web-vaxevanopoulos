<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides an "Indices Overview Tables" block.
 *
 * @Block(
 * 	id = "indices_overview",
 * 	admin_label = @Translation("Indices Overview Tables"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class IndicesOverviewTablesBlock extends BlockBase implements BlockPluginInterface {

	public function build() {
		$service = \Drupal::service('athex_d_mde.indices_overview_tables');
		return $service->getBlockRA();
	}
}
