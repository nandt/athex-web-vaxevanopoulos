<?php

namespace Drupal\athex_d_mde\AthexRendering;

use Drupal\Core\Url;


class LiveNavTabs {

	protected string $routeId;
	protected string $key;
	protected array $tabs;
	protected array $routeParams;
	protected BsNav $nav;

	function __construct(
		string $routeId,
		string $tabsKey,
		array $tabs,
		array $routeParams = [],
		string $class = 'tabs'
	) {
		$this->routeId = $routeId;
		$this->key = $tabsKey;
		$this->tabs = $tabs;
		$this->routeParams = $routeParams;
		$this->nav = new BsNav(
			$tabs, $tabs[0], $class
		);
	}

	public function render() {
		$nav = $this->nav->render();

		foreach ($this->tabs as $idx => $tab) {
			if (!is_int($idx)) continue;
			$url = Url::fromRoute(
				$this->routeId,
				[
					...$this->routeParams,
					$this->key => $tab
				]
			);
			$nav[$idx][0]['#attributes']['data-live-nav'] = $url->toString();
		}

		return [[
			'#type' => 'container',
			'#attributes' => [
				'class' => ['live-nav']
			],
			'#attached' => [
				'library' => [
					'athex_d_mde/live-nav'
				],
			],
			$nav
		]];
	}
}
