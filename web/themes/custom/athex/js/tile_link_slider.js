jQuery(document).ready(function ($) {

	let tileLinkSlider;

	function initSlider() {
		tileLinkSlider = tns({
			container: '.tileLinksWrapper',
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
			controlsContainer: '.tiles-slider',
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
	}

	initSlider();
})
