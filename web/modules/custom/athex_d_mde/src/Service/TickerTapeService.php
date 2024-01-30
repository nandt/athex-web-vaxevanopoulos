<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use GuzzleHttp\ClientInterface;

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
		$this->configFactory = $configFactory; // Store the config factory itself
		$this->api = $api;
		$this->renderer = $renderer;
	}
/*
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
*/
	/*public function getItemData($codes) {
		$codes = join(',', $codes);
		$items = $this->api->callDelayed('Info', ['code' => $codes]);
		var_dump($items); // This will print the structure of $items

		/*return array_map(function($item) {
			return [
				'symbol' => $item['instrSysName'], // e.g., 'ETE.ATH',
				'value' => $item['price'],
				'change' => $item['pricePrevClosePricePDelta']
			];
		}, $items);

		return array_map(function($item) {
			// Ensure that $item is an array and has the expected keys
			if (is_array($item) && isset($item['instrSysName'], $item['price'], $item['pricePrevClosePricePDelta'])) {
				return [
					'symbol' => $item['instrSysName'],
					'value' => $item['price'],
					'change' => $item['pricePrevClosePricePDelta']
				];
			} else {
				// Handle unexpected item format
				// Log error or return a default value
			}
		}, $items);
	}
	*/
public function getItemData($codes) {
	$codes = join(',', $codes);
	$items = $this->api->callDelayed('Info', ['code' => $codes]);
	//var_dump($items); // This will print the structure of $items

	$result = [];
	foreach ($items as $item) {
		// Check if the necessary keys exist
		if (isset($item['instrSysName'], $item['price'], $item['pricePrevClosePriceDelta'])) {
			$result[] = [
				'symbol' => $item['instrSysName'],
				'value' => $item['price'],
				'change' => $item['pricePrevClosePriceDelta']
			];
		}
	}
	return $result;
}


	public function getTapeItemData() {
		//TODO: get codes based on config
		$codes = ['ETE.ATH', 'ALPHA.ATH', 'TPEIR.ATH', 'EXAE.ATH'];

		return $this->getItemData($codes);
	}

	/*public function getTapeItemData() {
		// Fetch all 'instrCode' values
		$codes = $this->getAllInstrCodes();

		// Get detailed data for each code
		return $this->getItemData($codes);
	}
	*/

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

	/*public function getTapeItemRenderArray() {
		//TODO: get codes based on config
		//$codes = ['GD.ATH', 'FTSE.ATH', 'ETE.ATH', 'ALPHA.ATH','TPEIR.ATH','EXAE.ATH'];
                //$codes = ['GD.ATH','TPEIR.ATH','EXAE.ATH'];
		$config = $this->config->get('athex_d_mde.tickertape');
		$codes = $config->get('codes') ;
		return $this->getItemsRenderArray($codes);
	}
*/

	public function getTapeItemRenderArray() {
		$config = $this->configFactory->get('athex_d_mde.tickertape'); // Use the factory to get the 'athex_d_mde.tickertape' config
		$codesString = $config->get('codes') ?: 'GD.ATH,TPEIR.ATH,EXAE.ATH'; // Use a default value if 'codes' is not set
		$codes = explode(',', $codesString);

		return $this->getItemsRenderArray($codes);
	}

	/*public function getTapeItemRenderArray() {
    // Fetch codes from the configuration
    $config = $this->config->get('athex_d_mde.tickertape');
    //var_dump($config);
   // $codesString = $config->get('codes') ?: 'GD.ATH,TPEIR.ATH,EXAE.ATH'; // Default value if not set
     //  $codesString = $config->get('codes') ; // Default value if not set
    $codes = explode(',', $codesString);

    return $this->getItemsRenderArray($codes);
}
*/
	public function getMarketStatusData() {

     	$info = $this->api->callDelayed('MarketInfo', ['market' => 'ATH', 'instrument' => 'EQ']);
		//var_dump($info); // This will print the structure of $items
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
