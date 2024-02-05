<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\athex_d_mde\Service\IndicesOverviewTablesService;
use Drupal\athex_sis\Controller;
/**
 * Provides a 'IndicesOverviewTables' Block.
 *
 * @Block(
 *   id = "indices_overview_tables_block",
 *   admin_label = @Translation("Indices Overview Tables Block"),
 *   category = @Translation("Custom"),
 * )
 */
class IndicesOverviewTablesBlock1 extends BlockBase implements ContainerFactoryPluginInterface
{

	/**
	 * The Indices Overview Tables Service.
	 *
	 * @var \Drupal\athex_d_mde\Service\IndicesOverviewTablesService
	 */


	/**
	 * Constructs a new IndicesOverviewTablesBlock1.
	 *
	 * @param array $configuration
	 *   A configuration array containing information about the plugin instance.
	 * @param string $plugin_id
	 *   The plugin ID for the plugin instance.
	 * @param mixed $plugin_definition
	 *   The plugin implementation definition.
	 * @param \Drupal\athex_d_mde\Service\IndicesOverviewTablesService $indicesOverviewTablesService
	 *   The Indices Overview Tables Service.
	 */

	protected $sisDbDataService;
	protected $indicesOverviewTablesService;

	public function __construct(array $configuration, $plugin_id, $plugin_definition, SisDbDataService $sisDbDataService, IndicesOverviewTablesService $indicesOverviewTablesService)
	{
		parent::__construct($configuration, $plugin_id, $plugin_definition);
		$this->sisDbDataService = $sisDbDataService;
		$this->indicesOverviewTablesService = $indicesOverviewTablesService;
	}


	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
	{
		return new static(
			$configuration,
			$plugin_id,
			$plugin_definition,
			$container->get('athex_sis.db_data'), // This should be the fourth argument
			$container->get('athex_d_mde.indices_overview_tables') // This should be the fifth argument
		);
	}


	/**
	 * {@inheritdoc}
	 */

	/*public function build()
	{ $renderArray = [];

		// Assuming $gdValues is defined somewhere or you loop through an array of values
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE']; // Example GD values

		foreach ($gdValues as $gdValue) {
			// Define your SQL query within the loop, so it uses the current $gdValue
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
                JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
                JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
                WHERE hi.TICKER_EN = :gdValue";

			// Fetch data for each GD value using the correct method and parameters
			$data = $this->sisDbDataService->fetchAllWithParams($sql, [':gdValue' => $gdValue]);

			// Check if $data is an array and not empty before passing to getSubProductsTableRA
			if (is_array($data) && !empty($data)) {
				// Use the fetched data in IndicesOverviewTablesService
				$renderArray[$gdValue] = $this->indicesOverviewTablesService->getSubProductsTableRA($data);
			}
		}

		// Assuming you have a template named 'your_custom_block_template' to display this data
		return $renderArray;
	}*/

	/*public function build() {
		$renderArray = [];
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];

		foreach ($gdValues as $gdValue) {
			$sql = "SELECT hs.TICKER_EN FROM HELEX_STOCKS hs
        JOIN HELEX_INDEXCOMPOSITION hic ON hs.STOCK_ID = hic.STOCK_ID
        JOIN HELEX_INDICES hi ON hic.INDEX_ID = hi.INDEX_ID
        WHERE hi.TICKER_EN = :gdValue";

			$params = [':gdValue' => $gdValue]; // Replace 'someValue' with actual value

			$data = $this->sisDbDataService->fetchAllWithParams($sql, $params);

			if (is_array($data) && !empty($data)) {
				$renderArray[$gdValue] = $this->indicesOverviewTablesService->getSubProductsTableRA($data); // Assuming 'Risers' is a valid second argument
			} else {
				$renderArray[$gdValue] = []; // Ensure an empty array is set if no data
			}
		}

		return [
			'#theme' => 'indices_overview_tables_block1',
			'#renderArray' => $renderArray,
		];
	}*/

	public function build() {
		$renderArray = [];
		$gdValues = ['GD', 'FTSE', 'ETE', 'ALPHA', 'TPEIR', 'EXAE'];

		// Fetch the tables for all categories and GD values
		$allTables = $this->indicesOverviewTablesService->getSubProductsTables();

		foreach ($gdValues as $gdValue) {
			// Check if the data for this GD value exists
			if (isset($allTables[$gdValue])) {
				$renderArray[$gdValue] = [
					'Risers' => $allTables[$gdValue]['Risers'],
					'Fallers' => $allTables[$gdValue]['Fallers'],
					'Most Active' => $allTables[$gdValue]['Most Active'],
				];
			} else {
				// Ensure an empty array is set if no data for this GD value
				$renderArray[$gdValue] = [
					'Risers' => [],
					'Fallers' => [],
					'Most Active' => [],
				];
			}
		}

		return [
			'#theme' => 'indices_overview_tables_block1',
			'#renderArray' => $renderArray,
		];
	}


}


