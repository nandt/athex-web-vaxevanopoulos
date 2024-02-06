document.addEventListener('DOMContentLoaded', function () {
	const tabs = document.querySelectorAll('.tab-title');
	const firstTabContentId = tabs[0].getAttribute('data-tab-target-id');
	const firstTabContent = document.querySelector('#' + firstTabContentId);

	tabs[0].classList.add('active');
	firstTabContent.classList.add('active');

	tabs.forEach(tab => {
	  tab.addEventListener('click', () => {
		const targetId = tab.getAttribute('data-tab-target-id');
		const targetContent = document.querySelector('#' + targetId);

		document.querySelectorAll('.tab-content').forEach(tc => {
		  tc.classList.remove('active');
		});
		tabs.forEach(t => {
		  t.classList.remove('active');
		});

		tab.classList.add('active');
		targetContent.classList.add('active');
	  });
	});
  });
