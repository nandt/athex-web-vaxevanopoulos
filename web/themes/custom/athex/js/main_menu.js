jQuery(document).ready(function ($) {

	$(document).on('click', '.burger-btn', function (e) {
		$(this).parents('.layout-container').toggleClass('expanded');
		$(this).parent().siblings('.burger-mobile').toggleClass('mobile-menu-main-expanded');
		$(this).parents('.main-menu').siblings().children().toggleClass('mobile-menu-main-expanded');
		// e.target....attributes..["aria-expanded"] = String($(document.body).hasClass("mobile-menu-main-expanded"));
	});

	$(document).on('click', '.search-icon', function (e) {
		e.preventDefault();
		$(this).siblings('div').toggleClass('hidden');
	})
})

