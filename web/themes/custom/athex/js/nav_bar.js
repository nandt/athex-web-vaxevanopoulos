jQuery(document).ready(function ($) {

	$(document).on('click', '.top-level > li', function(e) {
		$(this).parents('.layout-container').addClass('expanded');
		$(this).parents('.layout-container').siblings().toggleClass('hidden-menu');
		$(this).toggleClass('expanded');
		$(this).siblings().toggleClass('hidden');
		$(this).siblings().removeClass('expanded');
		$(this).parents('.main-menu').siblings().toggleClass('hidden');
		$(this).parent('button').toggleClass('back-button');
		$('footer').toggleClass('hidden-menu');
		$('.gradient').toggleClass('hidden-menu');
		$('.is_Wrapper').addClass('hidden-menu');

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



const topLinksWrapper = document.querySelector(".top-links-wrapper");

window.addEventListener("scroll", function() {

  if (window.scrollY > 0) {

    topLinksWrapper.style.display = "none";
  } else {

    topLinksWrapper.style.display = "block";
  }
});

document.addEventListener("DOMContentLoaded", function() {
	var searchIcon = document.querySelector(".search-icon");
	var searchWindow = document.querySelector(".search-window");
	var closeButton = document.querySelector(".close-button");

	searchIcon.addEventListener("click", function() {
	  searchWindow.style.display = "block";
	});

	closeButton.addEventListener("click", function() {
	  searchWindow.style.display = "none";
	});
  });
