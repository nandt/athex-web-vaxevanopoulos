jQuery(document).ready(function ($) {

	var imageSlider;

	function initSlider() {
		imageSlider = tns({
			container: '.imagesliderWrapper',
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
			controlsContainer: '#images-slider',
			responsive: {
				1: {
					items: 1.35,
					slideBy: 1,
				},
				992: {
					items: 2.5,
					slideBy: 2
				}
			}
		})

	}

	initSlider();

	$(this).children('.tns-item').css( "width", "auto" );
})

