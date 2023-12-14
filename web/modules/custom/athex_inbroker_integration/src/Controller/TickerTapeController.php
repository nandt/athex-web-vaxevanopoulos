<?php

namespace Drupal\athex_inbroker_integration\Controller;

use Drupal\athex_inbroker_integration\Service\ApiDataService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;

class TickerTapeController extends ControllerBase {

	protected $api;

	public function __construct(
		ApiDataService $api
	) {
		$this->api = $api;
	}

	public static function create(ContainerInterface $container) {
		return new static(
			$container->get('athex_inbroker.api_data')
		);
	}

	public function getData() {
		$items = [
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
		];

		// $tickerCodes = 'ETE.ATH,ALPHA.ATH,TPEIR.ATH,EXAE.ATH';

		$info = $this->api->callDelayed('MarketInfo', ['market' => 'ATH', 'instrument' => 'EQ']);
		// $items = $this->api->callDelayed('Info', ['code' => $tickerCodes]);


		// $info =
		// // Τα πεδία που σας ενδιαφέρουν είναι
		// // •	closed (0/1 => Ανοικτή/Κλειστή)
		// // •	tradeDate (ημ/νια διαπραγμάτευσης)
		// // •	time (ώρα τελευταίας ενημέρωσης)
		// [
		// 	'closed' => $info['closed'],
		// 	'tradeDate' => $info['tradeDate'],
		// 	'time' => $info['time']
		// ];


		// $items =
		// // Τα πεδία που σας ενδιαφέρουν είναι:
		// // •	pricePrevClosePriceDelta (μεταβολή σε ευρώ, της τιμής, price, σε σχέση με την τιμή του προηγούμενου κλεισίματος, prevClosePrice)
		// // •	pricePrevClosePricePDelta (ποσοστιαία μεταβολή της τιμής, price, σε σχέση με την τιμή του προηγούμενου κλεισίματος, prevClosePrice)
		// // •	price (τιμή)
		// // •	totalVolume (συνολικός όγκος)
		// // •	totalTurnover (συνολική αξία)
		// // •	instrCode (ο κωδικός του συμβόλου στην επιλεγμένη γλώσσα)
		// // •	instrSysName (το συστεμικό όνομα του συμβόλου στο InBroker)
		// array_map(function($item) {

		// 	return [
		// 		'symbol' => $item['instrSysName'], // 'ETE.ATH',
		// 		'value' => $item['price'],
		// 		'change' => $item['pricePrevClosePricePDelta']
		// 	];
		// }, $items);

		$result = [
			'info' => $info,
			'items' => $items
		];

		$response = new CacheableJsonResponse($result);
		$response->getCacheableMetadata()->setCacheMaxAge(9);
		return $response;
	}
}
