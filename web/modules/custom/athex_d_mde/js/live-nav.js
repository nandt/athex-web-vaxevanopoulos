(function ($, Drupal, once) {
	'use strict';

	function initLiveNav(element) {
		var container = document.createElement('div');
		element.appendChild(container);
		var ul = $(element).children('ul');
		ul.find('a[data-live-nav]').each(
			function () {
				$(this).on('click', function (event) {
					event.preventDefault();
					ul.find('a')
						.removeClass('active')
						.attr('aria-selected', 'false');
					$(this)
						.addClass('active')
						.attr('aria-selected', 'true');
					var url = $(this).data('live-nav');
					fetch(url).then(
						function (response) {
							return response.text();
						}
					).then(
						function (data) {
							container.innerHTML = data;
							Drupal.attachBehaviors(container);
						}
					);
				});
			}
		);
		ul.find('a[data-live-nav]').first().trigger('click');
	}

	Drupal.behaviors.liveNavRender = {
		attach: function (context, settings) {
			console.log('live-nav', context);
			once('live-nav', '.live-nav', context).forEach(
				initLiveNav
			);
		}
	};
})(jQuery, Drupal, once);
