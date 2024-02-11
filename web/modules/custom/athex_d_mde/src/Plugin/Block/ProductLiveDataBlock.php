<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

use Drupal\athex_d_mde\AthexRendering\Helpers;


/**
 * Provides an "Product Live Data" block.
 *
 * @Block(
 * 	id = "product_live_data",
 * 	admin_label = @Translation("Product Live Data"),
 * 	category = @Translation("AthexGroup InBroker Integration")
 * )
 */
class ProductLiveDataBlock extends BlockBase implements BlockPluginInterface {

	public function build() {
		return [
			'#type' => 'container',
			[
				'#theme' => 'indices_overview_index_summary',
				'#symbol' => 'EXAE',
				'#value' => '1,167.93',
				'#since_open_value' => Helpers::renderDelta(17.89),
				'#since_open_percentage' => Helpers::renderDelta(1.46, '%'),
				'#since_close_value' => Helpers::renderDelta(204.11),
				'#since_close_percentage' => Helpers::renderDelta(6.05, '%')
			]
		];
		// $service = \Drupal::service('athex_d_mde.indices_overview_tables');
		// return $service->getBlockRA();
	}
}
