
document.addEventListener('DOMContentLoaded', (event) => {
    var testimonialWrappers = document.querySelectorAll('.testimonial');

    testimonialWrappers.forEach(function(testimonial) {
        var imageDiv = testimonial.querySelector('.testimonialPersonImage');

        if (imageDiv.innerHTML.trim() != "") {
            // Αν το imageDiv έχει περιεχόμενο, αλλάζει το χρώμα του κειμένου σε πράσινο
            var testimonialTextDiv = testimonial.querySelector('.testimonial-body');
            testimonialTextDiv.style.color = 'green';
        } else {
            // Αν το imageDiv δεν έχει περιεχόμενο, εφαρμόζει τις αλλαγές στο CSS του testimonial
            testimonial.style.display = 'flex';
            testimonial.style.flexDirection = 'column';
        }
    });
});

