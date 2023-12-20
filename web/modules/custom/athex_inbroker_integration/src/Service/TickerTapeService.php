<?php

namespace Drupal\athex_inbroker_integration\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use GuzzleHttp\ClientInterface;

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
		$this->logger = $loggerFactory->get('athex_inbroker');
		$this->config = $configFactory->get('athex_inbroker.settings');
		$this->api = $api;
		$this->renderer = $renderer;
  	}

	public function getItemData($codes) {
		//TODO: remove to get actual data
		return array_slice([
			[
				'symbol' => 'ETE.ATH',
				'value' => 5.1 + (rand(-5, 5) * 0.1),
				'change' => 1
			], [
				'symbol' => 'ALPHA.ATH',
				'value' => 1.2 + (rand(-5, 5) * 0.1),
				'change' => 0.5
			], [
				'symbol' => 'TPEIR.ATH',
				'value' => 2.3 + (rand(-5, 5) * 0.1),
				'change' => -0.5
			], [
				'symbol' => 'EXAE.ATH',
				'value' => 3.4 + (rand(-5, 5) * 0.1),
				'change' => 0
			]
		], 0, count($codes));

		$codes = join(',', $codes);
		$items = $this->api->callDelayed('Info', ['code' => $codes]);

		// Τα πεδία που σας ενδιαφέρουν είναι:
		// •	pricePrevClosePriceDelta (μεταβολή σε ευρώ, της τιμής, price, σε σχέση με την τιμή του προηγούμενου κλεισίματος, prevClosePrice)
		// •	pricePrevClosePricePDelta (ποσοστιαία μεταβολή της τιμής, price, σε σχέση με την τιμή του προηγούμενου κλεισίματος, prevClosePrice)
		// •	price (τιμή)
		// •	totalVolume (συνολικός όγκος)
		// •	totalTurnover (συνολική αξία)
		// •	instrCode (ο κωδικός του συμβόλου στην επιλεγμένη γλώσσα)
		// •	instrSysName (το συστεμικό όνομα του συμβόλου στο InBroker)
		return array_map(function($item) {
			return [
				'symbol' => $item['instrSysName'], // 'ETE.ATH',
				'value' => $item['price'],
				'change' => $item['pricePrevClosePricePDelta']
			];
		}, $items);
	}

	public function getTapeItemData() {
		//TODO: get codes based on config
		$codes = ['ETE.ATH', 'ALPHA.ATH', 'TPEIR.ATH', 'EXAE.ATH'];

		return $this->getItemData($codes);
	}

	public function getItemsRenderArray($codes) {
		$result = [];

		foreach ($this->getItemData($codes) as $product) {
			$item = [
				'#theme' => 'ticker_tape_item'
			];
			foreach ($product as $key => $value)
				$item['#' . $key] = $value;

			$result[] = $item;
		}

		return $result;
	}

	public function getTapeItemRenderArray() {
		//TODO: get codes based on config
		$codes = ['ETE.ATH', 'ALPHA.ATH', 'TPEIR.ATH', 'EXAE.ATH'];

		return $this->getItemsRenderArray($codes);
	}

	public function getMarketStatusData() {
		//TODO: remove to get actual data
		return [
			'closed' => 1,
			'tradeDate' => '2023-12-14',
			'time' => '12:34'
		];

		$info = $this->api->callDelayed('MarketInfo', ['market' => 'ATH', 'instrument' => 'EQ']);

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
		$codes = ['ETE.ATH'];

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
