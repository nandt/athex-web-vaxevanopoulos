<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;

use Drupal\athex_d_mde\AthexRendering\BsNav;
use Drupal\athex_d_mde\AthexRendering\ProductsTable;
use Drupal\athex_d_mde\AthexRendering\Helpers;
use Drupal\athex_inbroker\Service\ApiDataService;
use Drupal\athex_sis\Service\SisDbDataService;


enum TableType: string {
	case RISERS = 'Risers';
	case FALLERS = 'Fallers';
	case MOST_ACTIVE = 'Most Active';

    public static function fromValue(string $name) {
        foreach (self::cases() as $type) {
            if ($name === $type->value) {
                return $type;
            }
        }
		return null;
	}
}


class IndicesOverviewTablesService
{

	use StringTranslationTrait;

	protected LoggerChannelInterface $logger;
	protected SisDbDataService $sisDbDataService;
	protected IndicesOverviewService $indicesOverviewService;
	protected ApiDataService $api;
	protected LanguageManagerInterface $languageManager;
	// protected ConfigFactoryInterface $configFactory;
	protected array $gdValues;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		SisDbDataService              $sisDbDataService,
		IndicesOverviewService        $indicesOverviewService,
		ApiDataService                $apiDataService,
		LanguageManagerInterface      $languageManager,
		ConfigFactoryInterface        $configFactory
	)
	{
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->sisDbDataService = $sisDbDataService;
		$this->indicesOverviewService = $indicesOverviewService;
		$this->api = $apiDataService;
		$this->languageManager = $languageManager;
		// $this->configFactory = $configFactory; // Assign to class property
		$gdValuesString = $configFactory->get('athex_d_mde.settings')->get('gd_values');
		$this->gdValues = $gdValuesString ? explode(',', $gdValuesString) : [];

	}

	public function getSubproductsTable(string $gdValue, TableType $tableType)
	{
		$data = $this->sisDbDataService->fetchAllWithParams(
			"SELECT hs.TICKER_EN
				FROM HELEX_STOCKS hs
				JOIN HELEX_INDEXCOMPOSITION hic
					ON hs.STOCK_ID = hic.STOCK_ID
				JOIN HELEX_INDICES hi
					ON hic.INDEX_ID = hi.INDEX_ID
				WHERE hi.TICKER_EN = :gdValue
			",
			[':gdValue' => $gdValue]
		);
		return $this->prepareTable($data, $tableType);
	}

	private function prepareTable(array $data, TableType $type)
	{

		foreach ($data as $k => $entry) {
			$data[$k] = $entry['TICKER_EN'] . '.ATH';
		}

		$data = join(',', $data);
		$data = $this->api->callDelayed('Info', ['code' => $data, 'format' => 'json']);

		$sortVar = 'pricePrevClosePricePDelta';

		if ($type == 'Most Active')
			$sortVar = 'totalInstrVolume';

		if ($type == 'Fallers')
			usort($data, function ($a, $b) use ($sortVar) {
				return( $a[$sortVar] * 10000) - ($b[$sortVar] * 10000);
			});
		else
			usort($data, function ($a, $b) use ($sortVar) {
				return( $b[$sortVar] * 10000) - ($a[$sortVar] * 10000);
			});

		$tableRows = [];
		$cols = [
			'symbol',
			'value',
			'change_value',
			'change'
		];

		for ($i = 0; @$data[$i] && $i < 8; $i++) {
			$rowData = Helpers::getProductRenderVars($data[$i]);
			$row = [];
			foreach ($cols as $key)
				$row[] = $rowData[$key];
			$tableRows[] = $row;
		}

		$ra = (new ProductsTable($tableRows))->render();
		$ra['#cache'] = [ 'max-age' => 9 ];
		return $ra;
	}


	public function getBlockRA()
	{
		$container = $this->indicesOverviewService->createContainer('GD');
		$pillsContainer = $this->getSubProductsPillsRA(
			TableType::fromValue('Risers')->value
		);
		$tableContent = $this->getSubproductsTable(
			// $container->getSymbol(),
			'GD',
			// $pillsContainer->getSelected()
			TableType::fromValue('Risers')
		);
		$renderedBlock = $container->render(
			// $pillsContainer->render(
			[$pillsContainer,
				$tableContent
			]
			// )
		);

		// $this->logger->debug(
		// 	print_r($renderedBlock, true)
		// );

		return [$renderedBlock];
	}

	private function getSubProductsPillsRA($seldTable)
	{
		return (new BsNav(
			[
				TableType::RISERS->value,
				TableType::FALLERS->value,
				TableType::MOST_ACTIVE->value
			],
			$seldTable,
			'pills'
		))->render();
	}

}
