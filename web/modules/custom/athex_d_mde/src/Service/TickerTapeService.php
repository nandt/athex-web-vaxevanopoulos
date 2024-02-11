<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use GuzzleHttp\ClientInterface;
use Drupal\athex_d_mde\AthexRendering\Helpers;

use Drupal\athex_inbroker\Service\ApiDataService;


class TickerTapeService {

	protected $logger;
	protected $config;
	protected $api;
	protected $renderer;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface $configFactory,
		ApiDataService $api,
		RendererInterface $renderer
	) {
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->config = $configFactory->get('athex_d_mde.settings');
		$this->api = $api;
		$this->renderer = $renderer;
  	}

	public function getItemData($codes) {
		$codes = join(',', $codes);
		$items = $this->api->callDelayed('Info', ['code' => $codes]);
		//var_dump($items); // This will print the structure of $items

		$result = [];
		foreach ($items as $item) {
			$result[] = Helpers::getProductRenderVars($item);
		}
		return $result;
	}

	public function getItemsRenderArray($codes) {
		$result = [];

		$itemData = $this->getItemData($codes);

		// Log the item data for debugging
		$this->logger->info('Item data: ' . print_r($itemData, TRUE));

		foreach ($itemData as $product) {
			$item = [
				'#theme' => 'ticker_tape_item'
			];
			foreach ($product as $key => $value) {
				$item['#' . $key] = $value;
			}

			$result[] = $item;
		}

		return $result;
	}

	public function getAllInstrCodes($instrCode) {
		/*
		* $codes = join(',', $codes);
		$items = $this->api->callDelayed('Info', ['code' => $codes]);
		* */
		// Make an API call to fetch data that includes 'instrCode' for all items
		// The specifics of this call depend on the API you're working with
		// Assuming the API returns an array of items, each with an 'instrCode' field
		$instrCode = join(',', $instrCode);
		$allItems = $this->api->callDelayed('Info', ['instrCode' =>$instrCode]);
		$allCodes = [];

		foreach ($allItems as $item) {
			if (isset($item['instrCode'])) {
				$allCodes[] = $item['instrCode'];
			}
		}

		return $allCodes;
		//var_dump($allCodes); // This will print the structure of $items

	}

	public function getTapeItemData() {
		// Fetch all 'instrCode' values
		$codes = $this->getAllInstrCodes();

		// Get detailed data for each code
		return $this->getItemData($codes);
	}

	public function getTapeItemRenderArray() {
		//TODO: get codes based on config
		$codes = ['GD.ATH', 'FTSE.ATH', 'ETE.ATH', 'ALPHA.ATH','TPEIR.ATH','EXAE.ATH'];

		return $this->getItemsRenderArray($codes);
	}

	public function getMarketStatusData() {
		$info = $this->api->callDelayed('MarketInfo', ['market' => 'ATH', 'instrument' => 'EQ']);

		// var_dump($info); // This will print the structure of $items
		// Τα πεδία που σας ενδιαφέρουν είναι
		// •	closed (0/1 => Ανοικτή/Κλειστή)
		// •	tradeDate (ημ/νια διαπραγμάτευσης)
		// •	time (ώρα τελευταίας ενημέρωσης)

		return [
			'closed' => $info['closed'],
			'tradeDate' => $info['tradeDate'],
			'time' => $info['time']
		];
	}

	public function getPrimaryInfoRenderArray() {
		//TODO: get codes based on config
		$codes = ['GD.ATH'];

		$result = [
			'#theme' => 'ticker_tape_info',
			'#pinned_items' => $this->getItemsRenderArray($codes)
		];

		foreach ($this->getMarketStatusData() as $key => $value)
			$result['#' . $key] = $value;

		return $result;
	}

	public function getPrimaryInfoHtml() {
		return $this->renderer->renderPlain(
			$this->getPrimaryInfoRenderArray()
		);
	}
}
