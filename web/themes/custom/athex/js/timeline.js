let lastScrollTop = 0;

window.addEventListener('scroll', function() {
    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const verticalBars = document.querySelectorAll('.vertical-bar');
    const bullets = document.querySelectorAll('.bullet');


    const triggerPoint = window.innerHeight * 0.8;


    verticalBars.forEach((bar) => {
        const barPosition = bar.getBoundingClientRect().top + bar.offsetHeight / 2;
        if (barPosition < triggerPoint) {
            bar.classList.add('scrolled');
        } else if (currentScrollTop < lastScrollTop && barPosition >= triggerPoint) {
            bar.classList.remove('scrolled');
        }
    });


    bullets.forEach((bullet) => {
        const bulletPosition = bullet.getBoundingClientRect().top + bullet.offsetHeight / 2;
        if (bulletPosition < triggerPoint) {
            bullet.classList.add('bullet-active');
        } else if (currentScrollTop < lastScrollTop && bulletPosition >= triggerPoint) {
            bullet.classList.remove('bullet-active');
        }
    });

    lastScrollTop = currentScrollTop;
});
