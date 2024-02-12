(function ($, Drupal, once) {
	'use strict';

	var ticker;
	var urlElementMap = {};

	function tick() {
		Promise.all(
			Object.keys(urlElementMap).reduce(function (acc, url) {
				urlElementMap[url].forEach(function (element) {
					acc.push(
						fetch(url).then(function (response) {
							return response.text();
						}).then(function (html) {
							if (element.lastAppliedHtml === html) return;
							element.innerHTML = element.lastAppliedHtml = html;
							Drupal.attachBehaviors(element);
						}).catch(function() {})
					);
				});
				return acc;
			}, [])
		).then(
			function () {
				ticker = setTimeout(tick, 3000);
			}
		);
	}

	function initLiveRender(element) {
		var url = element.getAttribute('data-live-render');
		urlElementMap[url] = urlElementMap[url] || [];
		urlElementMap[url].push(element);
		if (!ticker) tick();
	}

	Drupal.behaviors.liveRender = {
		attach: function (context, settings) {
			once('live-render', '.live-render', context).forEach(
				initLiveRender
			);
		}
	};
})(jQuery, Drupal, once);
