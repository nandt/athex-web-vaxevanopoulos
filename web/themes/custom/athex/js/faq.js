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


document.addEventListener('DOMContentLoaded', function () {

  var downloadLinks = document.querySelectorAll('.downloadFile a');


  downloadLinks.forEach(function(link) {
    link.setAttribute('target', '_blank');
  });
});


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.faq-item').forEach(function(item) {
        var faqiBody = item.querySelector('.faq-content .col-12.col-lg-10 p');
        var tabContent = item.querySelector('.tabContent');
        if (!faqiBody || faqiBody.innerHTML.trim() === '') {
            tabContent.style.justifyContent = 'flex-end';
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.faq-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            this.classList.toggle('active-toggle');
        });
    });
});




