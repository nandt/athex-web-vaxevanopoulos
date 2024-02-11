(function ($, Drupal, once, tns) {
	Drupal.behaviors.tinySliderSolutions = {
		attach: function(context, settings) {
			once('tns-solutions-init', '.solWrapper', context).forEach(
				function(sliderNode) {
					tns({
						container: $(sliderNode).find('.solviewhomeWrapper')[0],
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
						controlsContainer: $(sliderNode).find('.solutions-slider')[0],
						responsive: {
							1: {
								items: 2,
								slideBy: 1
							},
							992: {
								items: 5,
								slideBy: 5,
								gutter: 8
							}
						}
					})
				}
			)
		}
	}

	const solItems = $('.solutions').find('.solviewhomeWrapper > .solhome-item');
		solItems.each(function (index) {
			const itemNumber = index + 1;
			$(this).find('a.sol').prepend($('<div class="number">' + '0' + itemNumber + '</div>'));
	});

})(jQuery, Drupal, once, tns);
