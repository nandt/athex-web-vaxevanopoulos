(function (Drupal, once) {
	Drupal.behaviors.tabSwitching = {
		attach: function (context, settings) {
			const navLinks = once('tab-switching', '.nav-item', context);

			navLinks.forEach(function (element, index) {
				element.addEventListener('click', function (e) {
					console.log('Tab clicked:', element);
					e.preventDefault();
					var targetId = 'tab-content-' + index; // Corresponding to the PHP change

					const tabContents = document.querySelectorAll('.tab-content');
					tabContents.forEach(function (content) {
						content.style.display = 'none';
					});

					const targetContent = document.getElementById(targetId);
					if (targetContent) {
						targetContent.style.display = 'block';
					}
				});

				// Automatically display the first tab's content
				if (index === 0) {
					document.getElementById('tab-content-0').style.display = 'block';
				}
			});
		}
	};
})(Drupal, once);


