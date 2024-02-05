<?php

namespace Drupal\athex_d_mde\Plugin\Block;

use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\athex_d_mde\Service\IndicesOverviewTablesService;
use Drupal\Core\Config\ConfigFactoryInterface;

// Import the ConfigFactory service

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
	protected $configFactory;


	public function __construct(array $configuration, $plugin_id, $plugin_definition, SisDbDataService $sisDbDataService, IndicesOverviewTablesService $indicesOverviewTablesService, ConfigFactoryInterface $configFactory)
	{
		parent::__construct($configuration, $plugin_id, $plugin_definition);
		$this->sisDbDataService = $sisDbDataService;
		$this->indicesOverviewTablesService = $indicesOverviewTablesService;
		$this->configFactory = $configFactory;
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
			$container->get('athex_d_mde.indices_overview_tables'),
			$container->get('config.factory')

		);
	}


	/**
	 * {@inheritdoc}
	 */


	public function build()
	{
		$config = $this->configFactory->get('athex_d_mde.settings');
		$gdValuesString = $config->get('gd_values');
		$gdValues = $gdValuesString ? explode(',', $gdValuesString) : []; // Use the settings from the config

		$renderArray = [];
		$allTables = $this->indicesOverviewTablesService->getSubProductsTables();


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


