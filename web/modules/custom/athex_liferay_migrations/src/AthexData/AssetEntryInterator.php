<?php

namespace Drupal\athex_liferay_migrations\AthexData;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\Service\ApiDataService;


define('ALMAI_PAGESIZE', 10);

class AssetEntryInterator implements \Iterator {

	protected ApiDataService $api;

    private $pageIdx = -1;
	private int $count = 0;
	private ?array $page = null;


	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
	}

	private function fetchCurrentPage() {
		return $this->api->call(
			ApiEndpoints::ASSETENTRY__GET_ENTRIES,
			[
				'start' => ($this->pageIdx * ALMAI_PAGESIZE) + 1,
				'end' => (($this->pageIdx + 1) * ALMAI_PAGESIZE) + 1
			]
		);
	}

	private function getPage() {
		if ($this->page !== null)
			return $this->page;

		++$this->pageIdx;

		$page = $this->fetchCurrentPage();

		if (empty($page))
			return $this->page = false;

		return $this->page = $page;
	}

	#[\ReturnTypeWillChange]
	public function current() {
		$page = $this->getPage();
		return $page ?: null;
	}

	public function valid(): bool {
		return !!$this->current();
	}

	public function next(): void {
		if (!$this->valid()) return;
		$this->page = null;
		++$this->count;
	}

	public function key() {
		return $this->count;
	}

	public function rewind(): void {
		$this->pageIdx = -1;
		$this->count = 0;
		$this->page = null;
	}
}
