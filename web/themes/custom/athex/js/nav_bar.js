jQuery(document).ready(function ($) {

	$(document).on('click', '.burger-mobile .menu-block--main > nav > ul > li', function(e) {
		$(this).parents('.main-menu').siblings().toggleClass('hidden');
		$(this).parents('.main-menu').toggleClass('expanded');
		$(this).siblings().removeClass('expanded');
		$(this).toggleClass('expanded');
		$(this).parent('button').toggleClass('back-button');
		$(this).siblings().toggleClass('hidden');
	});

	$(document).on('click', '.back-button', function(e) {
		$(this).parent().siblings('li').toggleClass('hidden');
	})

	$(document).on('click', '.expanded > div > ul > li', function(e){
		$(this).toggleClass('expanded');
		$(this).parents('.main-menu').toggleClass('expanded');
		$(this).siblings().toggleClass('hidden');
		$(this).parent().parent().parent('li').toggleClass('expanded');
		$(this).parent().parent().parent('li').siblings().toggleClass('hidden');
		$(this).parent().parent().parent().parent().siblings().toggleClass('hidden');
		$(this).parent().siblings().toggleClass('hidden');
	});

});

