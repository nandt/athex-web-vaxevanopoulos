(function ($, Drupal, once, tns) {
	Drupal.behaviors.tinySliderTileLinks = {
		attach: function(context, settings) {
			once('tns-tiles-init', '.tl-wrapper', context).forEach(
				function(sliderNode) {
					tns({
						container: $(sliderNode).find('.tileLinksWrapper')[0],
						loop: false,
						nav: false,
						autoplay: false,
						speed: 300,
						autoplayButtonOutput: false,
						mouseDrag: true,
						preventScrollOnTouch: 'auto',
						lazyload: true,
						rewind: false,
						controlsPosition: 'bottom',
						controlsContainer: $(sliderNode).find('.tiles-slider')[0],
						gutter: 8,
						responsive: {
							1: {
								items: 2,
								slideBy: 1,
							},
							992: {
								items: 5,
								slideBy: 1,
							}
						}
					})

    				const linkItems = $(sliderNode).find('.tileLinksWrapper > .tile-link');
					linkItems.each(function (index) {
						const itemNumber = index + 1;
						$(this).find('.tile-link-wrapper a').prepend($('<div class="number">' + '0' + itemNumber + '</div>'));
					});
				}
			)
		}
	}
})(jQuery, Drupal, once, tns);
