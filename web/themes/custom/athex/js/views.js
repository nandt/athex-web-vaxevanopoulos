(function ($, Drupal, once) {
	Drupal.behaviors.viewsModifications = {
		attach: function (context, settings) {
			once('befs-sidepanel-sync', 'details.bef--secondary', context).forEach(
				function(element) {
					$(element).on("toggle", function(e) {
						$(document.body).toggleClass("befs-open", e.target.open);
					});
				}
			);

			once('athex-vbo-mods', '.views-form form > .form-wrapper', context).forEach(
				function(element) {

					//TODO

					// if (!$(element.parentElement).find("table th.select-all.views-field input").length)
					// 	return;
				}
			);

			once('athex-bef-mods', '.bef--secondary > div', context).forEach(
				function(element) {
					var actions = $(element).parents("form").find(".form-actions");
					$(element).append(actions);
					$(element).prepend(
						$(`<h2>${element.parentElement.children[0].innerText}</h2>`)
					);
					var closeBtn = $('<span class="close" />');
					$(element).prepend(closeBtn);
					closeBtn.on("click", function(e) {
						$(element).parents(".bef--secondary").children("summary").click();
					});
				}
			);

			const solItems = $('.solutions').find('.solviewhomeWrapper > .solhome-item');
			solItems.each(function (index) {
			const itemNumber = index + 1;
			$(this).find('a.sol').prepend($('<div class="number">' + (itemNumber <= 9 ? '0' : '') + itemNumber + '</div>'));
	});
		}
	};
})(jQuery, Drupal, once);
