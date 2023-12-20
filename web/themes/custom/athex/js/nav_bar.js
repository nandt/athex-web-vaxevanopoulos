jQuery(document).ready(function ($) {

	$(document).on('click', '.top-level > li', function(e) {
		$(this).parents('.layout-container').addClass('expanded');
		$(this).toggleClass('expanded');
		$(this).siblings().toggleClass('hidden');
		$(this).siblings().removeClass('expanded');
		$(this).parents('.main-menu').siblings().toggleClass('hidden');
		$(this).parent('button').toggleClass('back-button');
	});

	$(document).on('click', '.back', function(e) {
		$(this).parent().siblings('li').toggleClass('hidden');
	})

	$(document).on('click', '.expanded > div > ul > li', function(e){
		$(this).toggleClass('expanded');
		$(this).parents('.nav-menu').toggleClass('expanded');
		$(this).siblings().toggleClass('hidden');
		$(this).parent().parent().parent('li').toggleClass('expanded');
		$(this).parent().parent().parent('li').siblings().toggleClass('hidden');
		$(this).parent().parent().parent().parent().siblings().toggleClass('hidden');
		$(this).parent().siblings().toggleClass('hidden');
	});
});

