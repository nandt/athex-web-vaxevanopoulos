document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.faq-toggle').forEach(function(toggle) {
	  toggle.addEventListener('click', function() {
		var content = this.nextElementSibling;
		content.classList.toggle('active');
		// Προσθήκη ή αφαίρεση μιας κλάσης για τον τίτλο όταν το content γίνεται active
		this.classList.toggle('active-title');
	  });
	});
  });
