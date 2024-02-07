(function ($, Drupal, once, tns) {
	Drupal.behaviors.tinySliderImage = {
		attach: function(context, settings) {
			once('tns-images-init', '.imageSlider', context).forEach(
				function(sliderNode) {
					tns ({
						container: $(sliderNode).find('.imagesliderWrapper')[0],
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
						controlsContainer: $(sliderNode).find('#images-slider')[0],
						responsive: {
							1: {
								items: 1.25,
								slideBy: 1,
							},
							992: {
								items: 2.5,
								slideBy: 2
							}
						}
					})
				}
			)
		}
	}
})(jQuery, Drupal, once, tns);
