jQuery(document).ready(function ($) {

	let lisSlider;

	function initSlider() {
		lisSlider = tns({
			container: '.tickertapeWrapper',
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
			responsive: {
				1: {
					items: 3,
					slideBy: 1,
					fixedWidth: 138
				},
				992: {
					items: 4,
					slideBy: 1,
					fixedWidth:148
				}
			}
		})
	}

	initSlider();
})
