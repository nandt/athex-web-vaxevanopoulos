<?php

namespace Drupal\athex_d_mde\Service;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\athex_d_mde\AthexRendering\Helpers;

use Drupal\athex_inbroker\Service\ApiDataService;


class TickerTapeService
{

	protected $logger;
	protected $configFactory;
	protected $api;
	protected $renderer;

	public function __construct(
		LoggerChannelFactoryInterface $loggerFactory,
		ConfigFactoryInterface        $configFactory,
		ApiDataService                $api,
		RendererInterface             $renderer
	)
	{
		$this->logger = $loggerFactory->get('athex_d_mde');
		$this->configFactory = $configFactory; // Store the config factory itself
		$this->api = $api;
		$this->renderer = $renderer;
	}

	private function getItemData(array $codes)
	{
		$items = $this->api->callDelayed('Info', ['code' => join(',', $codes)]);
		$result = [];
		foreach ($items as $item) {
			$result[] = Helpers::getProductRenderVars($item);
		}
		return $result;
	}

	public function getItemsRenderArray(array $itemData) {
		$result = [];

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

	public function getAllInstrCodes($instrCode)
	{
		$instrCode = join(',', $instrCode);
		$allItems = $this->api->callDelayed('Info', ['instrCode' => $instrCode]);
		$allCodes = [];

		foreach ($allItems as $item) {
			if (isset($item['instrCode'])) {
				$allCodes[] = $item['instrCode'];
			}
		}

		return $allCodes;
	}

	public function getTapeItemData()
	{
		$config = $this->configFactory->get('athex_d_mde.tickertape'); // Use the factory to get the 'athex_d_mde.tickertape' config
		$codesString = $config->get('codes') ?: 'GD.ATH,TPEIR.ATH,EXAE.ATH'; // Use a default value if 'codes' is not set
		$codes = explode(',', $codesString);
		return $this->getItemData($codes);
	}

	// public function getTapeItemRenderArray() {
	// 	return $this->getItemsRenderArray(
	// 		$this->getTapeItemData()
	// 	);
	// }

	public function getMarketStatusData()
	{

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

	public function getPrimaryInfoRenderArray()
	{
		$codes = ['GD.ATH'];

		$result = [
			'#theme' => 'ticker_tape_info',
			'#pinned_items' => $this->getItemsRenderArray(
				$this->getItemData($codes)
			)
		];

		foreach ($this->getMarketStatusData() as $key => $value)
			$result['#' . $key] = $value;

		return $result;
	}

	public function getPrimaryInfoHtml()
	{
		return $this->renderer->renderPlain(
			$this->getPrimaryInfoRenderArray()
		);
	}
}
