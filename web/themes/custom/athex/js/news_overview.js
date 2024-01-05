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
			items: 1,
			slideBy: 1
		})
	}

	$(window).on("resize load orientationchange", function(){
		if ($(window).width() < 992) {
			initSlider();
		} else {
			destroySlider();
		}
	});

	function destroySlider() {
		if (slider){
			slider.destroy();
		}
	}

})
