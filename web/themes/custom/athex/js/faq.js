document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.faq-toggle').forEach(function(toggle) {
	  toggle.addEventListener('click', function() {
		var content = this.nextElementSibling;
		content.classList.toggle('active');
		this.classList.toggle('active-title');
	  });
	});
  });
