jQuery(document).ready(function ($) {

	$(document).on('click', '.row > button.navbar-toggler', function (e) {
		$(this).siblings('.burger-mobile').toggleClass('mobile-menu-main-expanded');
		// e.target....attributes..["aria-expanded"] = String($(document.body).hasClass("mobile-menu-main-expanded"));
	});

	$(document).on('click', '.search-icon', function (e) {
		e.preventDefault();
		$(this).siblings('div').toggleClass('hidden');
	})
})

