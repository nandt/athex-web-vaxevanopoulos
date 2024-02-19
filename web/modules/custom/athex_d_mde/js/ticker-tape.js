(function ($, Drupal, once) {
	var tickerTapes = []
		, currentData = null
		, updater = null;

	function initTapeItem(template, data) {
		var item = document.createElement('div');
		item.innerHTML = template.innerHTML;
		item.textNodes = {};

		$("[data-placeholder]", item).replaceWith(function() {
			var node = document.createElement('span');
			item.textNodes[this.dataset.placeholder] = node;
			return node;
		});

		item.updateData = function (data) {
			Object.keys(item.textNodes).forEach(function (key) {
				var val = data[key] === undefined ? "-" : data[key];
				item.textNodes[key].innerHTML = val;
			});
		};

		item.updateData(data);

		return item;
	}

	function updateTickerTape(tapeIdx) {
		var tape = tickerTapes[tapeIdx];
		var items = Array.from(currentData.items);
		let childNodes = tape[1].childNodes;

		for (var i = 0; i < childNodes.length;) {
			var item = childNodes[i];
			var symbol = item.textNodes.symbol.textContent;
			var itemDataIdx = items.findIndex(function (dataItem) {
				return dataItem.symbol === symbol;
			});
			if (itemDataIdx < 0)
				return item.remove();

			item.updateData(items[itemDataIdx]);
			items.splice(itemDataIdx, 1);

			while (--itemDataIdx > -1) {
				item.parentNode.insertBefore(
					initTapeItem(tape[0], items.splice(0, 1)[0]),
					item
				);
				i++;
			}

			i++;
		}

		items.forEach(function (dataItem) {
			tape[1].appendChild(
				initTapeItem(tape[0], dataItem)
			);
		});
	}

	function handleData(data) {
		currentData = data;
		tickerTapes.forEach(function (tape, idx) {
			updateTickerTape(idx);
		});
	}

	function getData() {
		fetch("/fragments/tickerTapeData")
			.then(function (response) {
				return response.json();
			})
			.then(function (data) {
				handleData(data);
			})
			.catch(function (err) {
				console.error(err);
			})
			.finally(function () {
				updater = setTimeout(getData, 3000);
			});
	}

	function initUpdater() {
		if (updater) return;
		updater = setTimeout(getData(), 0);
	}

	function initTickerTape(element) {
		var container = document.createElement('div');
		element.replaceWith(container);
		Drupal.attachBehaviors(container);
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
