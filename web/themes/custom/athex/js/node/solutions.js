document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.scrollBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetElement = document.querySelector('#block-athex-content');
            if (targetElement) {
                const bottomPosition = targetElement.offsetTop + targetElement.offsetHeight;
                window.scrollTo({
                    top: bottomPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});

jQuery(document).ready(function ($) {
    const bullets = $('.bullet');
    const verticalBars = $('.vertical-bar');
    const sections = $('.section');
    const lastVerticalBar = $('.vertical-bar.last');

    setVerticalBarHeights();

    $(window).on('resize', setVerticalBarHeights);

    $(document).on('scroll', function () {
        let currentSectionIndex = 0;

        sections.each(function (index) {
            const rect = $(this).get(0).getBoundingClientRect();

            if (rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2) {
                currentSectionIndex = index;
            }
        });

        setActive(currentSectionIndex);
    });

    function scrollToSection(index) {
        sections.eq(index).get(0).scrollIntoView({ behavior: 'smooth' });
    }

      // On document ready
      $(document).ready(function() {
        getAndDisplayMarginRight(); // Initial call on page load

        // On window resize
        $(window).resize(function() {
          getAndDisplayMarginRight();
        });


        $(window).on('orientationchange', function() {
          getAndDisplayMarginRight();
        });

        $(window).on('load', function() {
            getAndDisplayMarginRight();
          });
      });
});




document.addEventListener("DOMContentLoaded", function() {
    var btnShare = document.querySelector('.btnShare');
    var bottomCTAs = document.querySelector('.bottomCTAs');

    var observer = new IntersectionObserver(function(entries) {

        if(entries[0].intersectionRatio === 0) {
            bottomCTAs.style.display = 'flex';
        } else {
            bottomCTAs.style.display = 'none';
        }
    }, { threshold: [0] });

    observer.observe(btnShare);
});




