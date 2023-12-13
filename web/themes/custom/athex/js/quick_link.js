jQuery(document).ready(function ($) {

	var slider;

	function initSlider() {
		slider = tns({
			container: '.qlWrapper',
			loop: false,
			items: 1,
			nav: false,
			autoplay: false,
			speed: 400,
			autoplayButtonOutput: false,
			mouseDrag: true,
			preventScrollOnTouch: 'auto',
			lazyload: true,
			controlsContainer: "#customize-controls"
		});
	}

	function destroySlider() {
		if (slider)
			slider.destroy();
	}

	$(window).on("resize load orientationchange", function () {
		if ($(window).width() < 992)
			initSlider();
		else
			destroySlider();
	});
});
