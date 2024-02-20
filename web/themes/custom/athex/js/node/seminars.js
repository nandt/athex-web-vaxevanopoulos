var slider = tns({
	container: '.my-slider',
	items: 1.25,
	mode: 'carousel',
	slideBy: 1,
	autoplay: false,
	mouseDrag: true,
	autoplayButtonOutput: false,
	controls: true,
	nav: false,
	gutter: 15
});

jQuery(document).ready(function ($) {
	registerBtn = $(document).find('#scrollToForm');
	form = $(document).find('.webformWrapper_aaq').parent();

	registerBtn.on("click", function() {
		$("html, body").animate(
		{
			scrollTop: form.offset().top
		},
		100
		);
	});
});

  var controls = document.querySelectorAll('.tns-outer [aria-controls]');

   controls.forEach(function(control) {
    if (control.getAttribute('data-controls') === 'prev') {
      control.textContent = '<';
    } else if (control.getAttribute('data-controls') === 'next') {
      control.textContent = '>';
    }
  });

  window.addEventListener('DOMContentLoaded', (event) => {
	const seminarDetails = document.getElementById('seminarDetails');
	const originalPosition = seminarDetails.getBoundingClientRect().top + window.scrollY;

	window.addEventListener('scroll', () => {
	  let scrollY = window.scrollY;
	  if (scrollY >= originalPosition) {
		seminarDetails.style.position = 'fixed';
		seminarDetails.style.top = '0';
	  } else {
		seminarDetails.style.position = 'relative';
		seminarDetails.style.top = '';
	  }
	});
  });

  window.addEventListener('DOMContentLoaded', (event) => {
    const seminarDetails = document.getElementById('seminarDetails');
    const leftSideItems = document.querySelector('.LeftSideItemElements');
    const originalPosition = seminarDetails.getBoundingClientRect().top + window.scrollY;
    const additionalOffset = 70;

    window.addEventListener('scroll', () => {
        let scrollY = window.scrollY;
        let stopPosition = leftSideItems.getBoundingClientRect().bottom + window.scrollY - seminarDetails.offsetHeight - additionalOffset;

        if (scrollY >= originalPosition && scrollY < stopPosition) {
            seminarDetails.style.position = 'fixed';
            seminarDetails.style.top = '0';
            seminarDetails.style.marginTop = '120px';
        } else if (scrollY >= stopPosition) {
            seminarDetails.style.position = 'absolute';
            seminarDetails.style.top = `${stopPosition - originalPosition}px`;
            seminarDetails.style.marginTop = '0';
        } else {
            seminarDetails.style.position = 'relative';
            seminarDetails.style.top = '';
            seminarDetails.style.marginTop = '0';
        }
    });
});



