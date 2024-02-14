document.addEventListener('DOMContentLoaded', function() {

    let itemCount = 1;

    document.querySelectorAll('.prefooter-p_item.numberOfItems').forEach(function(item) {
        const currentItemClass = `numberOfItems${itemCount}`;

        item.classList.add(currentItemClass);

        const webform = item.nextElementSibling;
        if (webform && webform.classList.contains('webform')) {
            webform.classList.add(currentItemClass);
        }

        item.querySelector('.prefooter-p_item.body').addEventListener('click', function() {
            document.querySelectorAll('.prefooter-p_item.webform').forEach(function(form) {
                form.style.display = 'none';
            });
            const formToShow = document.querySelector(`.prefooter-p_item.webform.${currentItemClass}`);
            if (formToShow) {
                formToShow.style.display = 'block';
            }
            const overlay = document.querySelector('.overlay');
            if (overlay) {
                overlay.style.display = 'block';
            }
        });

        itemCount++;
    });

    document.querySelectorAll('.close-btn').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            const webformToHide = this.closest('.prefooter-p_item.webform');
            if (webformToHide) {
                webformToHide.style.display = 'none';
            }

            const overlay = document.querySelector('.overlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        });
    });
});


let overlay = document.querySelector('.overlay');
if (!overlay) {
	overlay = document.createElement('div');
	overlay.className = 'overlay';
	document.body.appendChild(overlay);

	overlay.addEventListener('click', function() {
		document.querySelectorAll('.prefooter-p_item.webform').forEach(function(webform) {
			webform.style.display = 'none';
		});
		overlay.style.display = 'none';
	});
}

