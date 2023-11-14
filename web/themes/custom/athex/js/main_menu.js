jQuery(document).ready(function ($) {

	$(document).on('click', '.row > button.navbar-toggler', function (e) {
		$(this).siblings('.burger-mobile').toggleClass('mobile-menu-main-expanded');
		// e.target....attributes..["aria-expanded"] = String($(document.body).hasClass("mobile-menu-main-expanded"));
	});

})

