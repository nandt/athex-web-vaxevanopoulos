jQuery(document).ready(function ($) {

    var listingsSlider;

    listingsSlider = tns({
        container: '.listingsviewhomeWrapper',
        loop: false,
        items: 1,
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
    });

    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.url.indexOf('/views/ajax') !== -1) {
			listingsSlider.destroy();
			listingsSlider = tns({
            container: '.listingsviewhomeWrapper',
            loop: false,
            items: 1,
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
        });}
    });

	$(document).on('click', '.option', function (e) {
		$(this).addClass('active');
	})

	$(document).on('click', '.option', function (e) {
		setTimeout( function(e) {
			$('[id^="edit-submit-listings-bells"]').click();
		}, 100);
	})

})
