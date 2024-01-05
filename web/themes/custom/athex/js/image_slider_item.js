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
			rewind: true,
			controlsPosition: 'bottom',
			controlsContainer: '#images-slider',
			responsive: {
				1: {
					items: 1,
					slideBy: 1,
					//gutter: 24
				},
				992: {
					items: 3,
					slideBy: 1
				}
			}
		})
	}

	initSlider();

	// $(document).on('click', '.controls > .next', function() {
	// 	$(this).sibling().children('.imagesliderWrapper').children('.tns-slide-active').next().toggleClass('active');
	// })

	//$(document).find('.imagesliderWrapper').children('.tns-slide-active').prev().removeClas('active');
})

