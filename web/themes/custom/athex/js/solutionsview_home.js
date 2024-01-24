jQuery(document).ready(function ($) {

	let solSlider;

	function initSlider() {
		solSlider = tns({
			container: '.solviewhomeWrapper',
			loop: false,
			nav: false,
			autoplay: false,
			speed: 400,
			autoplayButtonOutput: false,
			mouseDrag: true,
			preventScrollOnTouch: 'auto',
			lazyload: true,
			controlsPosition: 'bottom',
			controlsContainer: '.solutions-slider',
			responsive: {
				1: {
					items: 2,
					slideBy: 2
				},
				992: {
					items: 5,
					slideBy: 5
				}
			}
		})
	}

	initSlider();

})
