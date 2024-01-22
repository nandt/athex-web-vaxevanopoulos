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
			controlsContainer: '#solutions-slider',
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

	const slides = $(document).find('.solviewhomeWrapper').children();
	slides.each(function(index) {
		solNumber = index + 1;

		$(this).children('.sol-teaser-number').prepend("test" + solNumber);
	})
})
