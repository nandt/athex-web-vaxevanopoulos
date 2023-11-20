(function ($, Drupal, once) {
	var tickerTapes = []
		, currentData = null
		, updater = null;

	function initItem(template) {
		var item = document.createElement('div');
		item.innerHTML = template.innerHTML;
		item.textNodes = {};

		$("[data-placeholder]", item).replaceWith(function() {
			var node = document.createTextNode("");
			item.textNodes[this.dataset.placeholder] = node;
			return node;
		});

		item.updateData = function (data) {
			Object.keys(item.textNodes).forEach(function (key) {
				item.textNodes[key].textContent = data[key] || "";
			});
		};

		return item;
	}

	function updateTickerTape(tapeIdx) {
		var data = Array.from(currentData);
		var tape = tickerTapes[tapeIdx];
		tape[1].childNodes.forEach(function (item) {
			var symbol = item.textNodes.symbol.textContent;
			var itemDataIdx = data.findIndex(function (dataItem) {
				return dataItem.symbol === symbol;
			});
			if (itemDataIdx < 0)
				return item.remove();

			item.updateData(data[itemDataIdx]);
			data.splice(itemDataIdx, 1);

			while (--itemDataIdx > -1) {
				var newItem = initItem(tape[0]);
				newItem.updateData(data[0]);
				item.prepend(newItem);
			}
		});
	}

	function handleSseMessage(data) {
		currentData = data;
		tickerTapes.forEach(function (tape, idx) {
			updateTickerTape(idx);
		});
	}

	function initUpdater() {
		if (updater) return;
		// updater = new EventSource('/sse');
		// updater.addEventListener('message', function (event) {
		// 	handleSseMessage(JSON.parse(event.data));
		// });
	}

	function initTickerTape(element) {
		var container = document.createElement('div');
		element.replaceWith(container);
		var tapeIdx = tickerTapes.push([element, container]) - 1;

		if (currentData)
			updateTickerTape(tapeIdx);
		else
			initUpdater();
	}

	Drupal.behaviors.inBrokerTickerTape = {
		attach: function (context, settings) {
			once('inbroker-init', 'div.ticker-item-template', context).forEach(
				initTickerTape
			);
		}
	};
})(jQuery, Drupal, once);
