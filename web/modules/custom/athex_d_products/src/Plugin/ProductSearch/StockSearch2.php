<?php

namespace Drupal\athex_d_products\Plugin\ProductSearch;

use Drupal\athex_d_products\Annotation\ProductSearch;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\athex_sis\Service\SisDbDataService;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Provides a Stock Search plugin.
 *
 * @ProductSearch(
 *   id = "stocksearch23",
 *   label = @Translation("Stock Search"),
 *   description = @Translation("Provides a search for stocks.")
 * )
 */
class StockSearch2 implements ProductSearchInterface, ContainerFactoryPluginInterface
{
	use StringTranslationTrait;


	protected $logger;
	protected $pluginDefinition;

	public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
	{
		return new static(
			$configuration,
			$plugin_id,
			$plugin_definition,
			$container->get('athex_sis.db_data'),
			$container->get('logger.factory')->get('athex_d_products'),
			$container->get('string_translation')
		);
	}


	public function __construct(array $configuration, $plugin_id, $plugin_definition, SisDbDataService $sisdb, LoggerInterface $logger, TranslationInterface $string_translation)
	{
		//parent::__construct($configuration, $plugin_id, $plugin_definition);
		$this->sisdb = $sisdb;
		$this->logger = $logger;
		$this->stringTranslation = $string_translation;
		$this->logger->debug('StockSearch23 plugin instantiated');
	}

	public function getLabel()
	{
		// Check if the 'label' key exists and is an instance of TranslatableMarkup.
		if (isset($this->pluginDefinition['label']) && $this->pluginDefinition['label'] instanceof \Drupal\Core\StringTranslation\TranslatableMarkup) {
			// Render the TranslatableMarkup object to get the translated string.
			return $this->pluginDefinition['label']->render();
		}
		// Provide a default label as a fallback.
		return t('Stock search23');
	}


	public function getQuery(array $filters, int $offset, int $limit)
	{
		$currentRequest = \Drupal::request();
		// Retrieve the 'letter' query parameter
		$letterFilter = $currentRequest->query->get('letter', '');

		// Implement your query logic here
		$sql = <<<SQL
    SELECT
        s.TICKER_EN AS "Symbol",
        s.ISIN AS "ISIN",
        c.NAME_EN AS "Issuer",
        CASE
            WHEN m.SIS_CODE = 30117 THEN 'SECURITIES MARKET'
            WHEN m.SIS_CODE = 30250 THEN 'ALTERNATIVE MARKET'
            ELSE 'Other'
        END AS "Market",
        h.close_value AS "Last Price",
        h.trading_date AS "Last Trading Date",
        h.change_percentage AS "Percentage"
    FROM
        NS_STOCKS s
    JOIN
        NS_MARKETS m ON s.MARKET_ID = m.SIS_CODE
    JOIN
        ns_companies c ON s.COMPANY_ID = c.SIS_CODE
    LEFT JOIN (
        SELECT
            stock_id,
            close_value,
            trading_date,
            change_percentage,
            ROW_NUMBER() OVER (PARTITION BY stock_id ORDER BY trading_date DESC) AS rn
        FROM
            HELEX_STOCKS_HISTORY
    ) h ON s.SIS_CODE = h.stock_id AND h.rn = 1
    WHERE
        (s.ISIN LIKE :searchValue OR s.TICKER_EN LIKE :searchValue OR s.NAME_EN LIKE :searchValue)
        AND (:marketFilter IS NULL OR m.SIS_CODE = :marketFilter)
        AND (:minPrice IS NULL OR h.close_value >= :minPrice)
        AND (:maxPrice IS NULL OR h.close_value <= :maxPrice)
        AND (:letterFilter IS NULL OR s.TICKER_EN LIKE :letterFilter) -- Filter by the first letter of the symbol
    SQL;

		$params = [
			':searchValue' => '%' . (isset($filters['searchValue']) ? $filters['searchValue'] : '') . '%',
			':marketFilter' => isset($filters['market']) ? $filters['market'] : null,
			':minPrice' => isset($filters['minPrice']) ? $filters['minPrice'] : null,
			':maxPrice' => isset($filters['maxPrice']) ? $filters['maxPrice'] : null,
			//':letterFilter' => isset($filters['letterFilter']) && $filters['letterFilter'] != 'All' ? $filters['letterFilter'] . '%' : null,
			':letterFilter' => $letterFilter !== 'All' ? $letterFilter . '%' : null,
		];

		$result = $this->sisdb->fetchAllWithParams($sql, $params);
		\Drupal::logger('queryResult')->notice('<pre>' . print_r($result, TRUE) . '</pre>');
		return $result ?: [];


	}

	public function getFilters()
	{
		return [
			'searchValue' => [
				'title' => $this->t('Search'),
				'type' => 'textfield',
				'placeholder' => $this->t("Type here to search"),
			],
			'market' => [
				'#type' => 'checkboxes', // Use # for properties
				'#title' => $this->t('Market'),
				'#options' => [
					'30117' => $this->t('SECURITIES MARKET'),
					'30250' => $this->t('ALTERNATIVE MARKET'),
					// Add more options as needed
				],
				'#default_value' => [], // You can set default values here if needed
			],
			'minPrice' => [
				'title' => $this->t('Min Price'),
				'type' => 'textfield',
			],
			'maxPrice' => [
				'title' => $this->t('Max Price'),
				'type' => 'textfield',
			],

		];
	}


	public function getHeaders()
	{
		return [
			['data' => $this->t('Symbol'), 'field' => 'Symbol'],
			['data' => $this->t('ISIN'), 'field' => 'ISIN'],
			['data' => $this->t('Issuer'), 'field' => 'Issuer'],
			['data' => $this->t('Market'), 'field' => 'Market'],
			['data' => $this->t('Last Price'), 'field' => 'Last Price'],
			['data' => $this->t('Last Trading Date'), 'field' => 'Last Trading Date'],
			['data' => $this->t('Percentage'), 'field' => 'Percentage'],
		];
	}


	public function getTableColumns()
	{
		// Implement your table columns logic here
		return [];
	}

	public function getRowTemplate()
	{
		\Drupal::logger('pluginDefinition')->notice('<pre>' . print_r($this->pluginDefinition, TRUE) . '</pre>');

		// Implement your row template logic here
		return null;
	}
	// Implement other necessary methods.
}
