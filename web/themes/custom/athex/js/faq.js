document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.faq-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const currentlyActiveContent = document.querySelector('.faq-content.active');
            const currentlyActiveTitle = document.querySelector('.faq-toggle.active-title');
            if (currentlyActiveContent && currentlyActiveContent !== this.nextElementSibling) {
                currentlyActiveContent.classList.remove('active');
                if (currentlyActiveTitle) {
                    currentlyActiveTitle.classList.remove('active-title');
                }
            }

            var content = this.nextElementSibling;
            content.classList.toggle('active');
            this.classList.toggle('active-title');
        });
    });
});
