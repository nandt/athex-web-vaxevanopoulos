<?php

namespace Drupal\athex_liferay_migrations\AthexLiferayIterator\Base;

use Drupal\athex_liferay_migrations\ApiEndpoints;
use Drupal\athex_liferay_migrations\Service\ApiDataService;

define('ALMAI_PAGESIZE', 1000);


abstract class LiferayApiPagesIterator implements \Iterator {

	private ApiDataService $api;

    protected $pageIdx = -1;
	private int $count = 0;
	private $page = null;


	public function __construct() {
		$this->api = \Drupal::service('athex_liferay_migrations.api_data');
	}

	protected abstract function pageFetcher();

	protected function fetchCurrentPage(ApiEndpoints $endpoint, array $params = []) {
		return $this->api->call(
			$endpoint,
			[
				'start' => ($this->pageIdx * ALMAI_PAGESIZE) + 1,
				'end' => (($this->pageIdx + 1) * ALMAI_PAGESIZE) + 1,
				...$params
			]
		);
	}

	private function getPage() {
		if ($this->page !== null)
			return $this->page;

		++$this->pageIdx;

		$page = $this->pageFetcher();

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
