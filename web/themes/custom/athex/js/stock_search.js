jQuery(document).ready(function ($) {
    let stocks =  $('.stocks');
    stocks.parents().parent().css('overflow-x', 'hidden')
    
    let nav_pills = stocks.find('.nav-pills');
    nav_pills.addClass('pillsWrapper')

    let pillsSlider;

	function initSlider() {
		pillsSlider = tns({
			container: '.pillsWrapper',
            loop: false,
            nav: false,
            autoplay: false,
            speed: 400,
            autoplayButtonOutput: false,
            mouseDrag: true,
            preventScrollOnTouch: 'auto',
            lazyload: true,
            controls:false,
            responsive: {
                1: {
                    items: 5,
                    slideBy: 4,
                },
                370: {
                    items: 8,
                    slideBy: 7,
                },
                1200: {
                    items: 27
                }
            }
		})

	}
	initSlider();
})