 var slider = tns({
    container: '.my-slider',
    items: 1.25,
    mode: 'carousel',
    slideBy: 'page',
    autoplay: false,
    mouseDrag: true,
    autoplayButtonOutput: false,
    controls: true,
    nav: false
  });

  var controls = document.querySelectorAll('.tns-outer [aria-controls]');

   controls.forEach(function(control) {
    if (control.getAttribute('data-controls') === 'prev') {
      control.textContent = '<'; // Σύμβολο για το Prev
    } else if (control.getAttribute('data-controls') === 'next') {
      control.textContent = '>'; // Σύμβολο για το Next
    }
  });
