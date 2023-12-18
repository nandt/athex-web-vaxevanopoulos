jQuery(document).ready(function ($) {

	let lisSlider;

	function initSlider() {
		lisSlider = tns({
			container: '.listingsviewhomeWrapper',
			loop: false,
			nav: false,
			autoplay: false,
			speed: 400,
			autoplayButtonOutput: false,
			mouseDrag: true,
			preventScrollOnTouch: 'auto',
			lazyload: true,
			controlsPosition: 'bottom',
			controlsContainer: '#listings-slider',
			responsive: {
				1: {
					items: 1,
					slideBy: 1
				},
				992: {
					items: 3,
					slideBy: 3
				}
			}
		})
	}

	initSlider();
})
