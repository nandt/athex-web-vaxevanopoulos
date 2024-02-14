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
		$(this).siblings().removeClass('expanded');
		$(this).parent().parent().parent('li').toggleClass('expanded');
		$(this).parent().parent().parent('li').siblings().toggleClass('hidden');
		$(this).parent().parent().parent().parent().siblings().toggleClass('hidden');
		$(this).parent().siblings().toggleClass('hidden');
	});
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

  document.querySelector('.burger-btn').addEventListener('click', function() {
    document.querySelector('.nav-menu').classList.toggle('active');
});

document.querySelector('.burger-icon').addEventListener('click', function() {
    document.querySelector('.col-12.nav-menu').classList.toggle('active');
});



const topMenuBar = document.querySelector('.top-menu-bar');
let lastScrollPosition = window.scrollY;

function handleScroll() {
  const currentScrollPosition = window.scrollY;

  if (currentScrollPosition > lastScrollPosition) {
    topMenuBar.style.display = 'none';
  } else {
    if (currentScrollPosition <= 0) {
      topMenuBar.style.display = 'block';
    }
  }

  lastScrollPosition = currentScrollPosition;
}

window.addEventListener('scroll', handleScroll);


