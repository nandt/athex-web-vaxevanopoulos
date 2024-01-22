jQuery(document).ready(function ($) {

	var imageSlider;

	function initSlider() {
		imageSlider = tns({
			container: '.newsViewTeaserWrapper',
			loop: false,
			nav: false,
			autoplay: false,
			speed: 300,
			autoplayButtonOutput: false,
			mouseDrag: true,
			preventScrollOnTouch: 'auto',
			lazyload: true,
			rewind: true,
			controlsPosition: 'bottom',
			controlsContainer: '#news-teaser-slider',
			responsive: {
				1: {
					items: 1,
					slideBy: 1,
				},
				992: {
					items: 3,
					slideBy: 1
				}
			}
		})
	}

	initSlider();
})
