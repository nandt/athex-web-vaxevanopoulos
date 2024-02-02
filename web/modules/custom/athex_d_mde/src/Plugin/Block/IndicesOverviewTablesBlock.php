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
		$loggerFactory = \Drupal::service('logger.factory');
		$logger = $loggerFactory->get('athex_d_mde');
		$logger->info('Building the Indices Overview Tables block.');

		$service = \Drupal::service('athex_d_mde.indices_overview_tables');
		$block_content = $service->getBlockRA();

		if (!empty($block_content)) {
			$logger->info('Block content was built successfully.');
		} else {
			$logger->warning('Block content was not built. Check the athex_d_mde.indices_overview_tables service.');
		}

		return $block_content;
	}


}
