(function (Drupal, once) {
	Drupal.behaviors.tabSwitching = {
		attach: function (context, settings) {
			// Initially hide all tab contents
			const tabContents = document.querySelectorAll('.tab-content', context);
			tabContents.forEach(function(content) {
				content.style.display = 'none';
			});

			// Find all the tabs
			const tabs = document.querySelectorAll('.nav-link', context);

			// Add click handlers to tabs
			tabs.forEach(function(tab, index) {
				tab.addEventListener('click', function(e) {
					e.preventDefault();

					// Extract the target ID from the href attribute
					const href = tab.getAttribute('href');
					if (!href || href.length <= 1 || href.charAt(0) !== '#') {
						console.error('Invalid href for tab:', href);
						return; // Skip this tab if href is invalid
					}
					const targetId = href.substring(1);

					// Hide all contents
					tabContents.forEach(function(content) {
						content.style.display = 'none';
					});

					// Show the clicked tab's content
					const targetContent = document.getElementById(targetId);
					if (targetContent) {
						targetContent.style.display = 'block';
					} else {
						console.error('Content not found for ID:', targetId);
					}
				});

				// Automatically display the first tab's content
				if (index === 0 && tab.getAttribute('href')) {
					const firstTabContentId = tab.getAttribute('href').substring(1);
					const firstTabContent = document.getElementById(firstTabContentId);
					if (firstTabContent) {
						firstTabContent.style.display = 'block';
					}
				}
			});
		}
	};
})(Drupal, once);
