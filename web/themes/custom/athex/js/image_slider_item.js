jQuery(document).ready(function ($) {

	var imageSlider;

	function initSlider() {
		imageSlider = tns({
			container: '.imagesliderWrapper',
			loop: false,
			nav: false,
			autoplay: true,
			speed: 300,
			autoplayButtonOutput: false,
			mouseDrag: true,
			preventScrollOnTouch: 'auto',
			lazyload: true,
			controls: false,
			rewind: true,
			controlsPosition: 'bottom',
			controlsContainer: '#images-slider',
			responsive: {
				1: {
					items: 2,
					slideBy: 1,
					gutter: 24
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

