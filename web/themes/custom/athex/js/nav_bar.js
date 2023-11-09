jQuery(document).ready(function ($) {

	$(document).on('click', '.menu-block--main > button', function (e) {
		$(document.body).toggleClass('mobile-menu-main-expanded');
		// e.target....attributes..["aria-expanded"] = String($(document.body).hasClass("mobile-menu-main-expanded"));
	});

	$(document).on('click', '.menu-block--main > nav > ul > li', function(e) {
		$(this).children().toggleClass('expanded main-item');
	});

	$(document).on('click', '.expanded > ul > li', function(e){
		$(this).parent().parent().toggleClass('expanded')
		$(this).children('div').toggleClass('expanded');
		//$(this).children('span').toggleClass('expanded');
	});

});

