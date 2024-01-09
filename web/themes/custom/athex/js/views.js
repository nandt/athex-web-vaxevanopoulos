(function ($, Drupal, once) {


	Drupal.behaviors.inBrokerTickerTape = {
		attach: function (context, settings) {
			$(context).find("details.bef--secondary").on("toggle", function (e) {
				$(document.body).toggleClass("befs-open", e.target.open);
			})
		}
	};
})(jQuery, Drupal, once);
