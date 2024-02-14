document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.prefooter-p_item.body').forEach(function(bodyElement) {
        bodyElement.addEventListener('click', function() {

            document.querySelectorAll('.prefooter-p_item.webform').forEach(function(webform) {
                webform.style.display = 'none';
            });

            const webform = document.querySelector('.prefooter-p_item.webform');
            webform.style.display = 'block';

            const overlay = document.querySelector('.overlay');
            overlay.style.display = 'block';
        });
    });

    document.querySelectorAll('.prefooter-p_item.webform .close-btn').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            const webform = this.closest('.prefooter-p_item.webform');
            webform.style.display = 'none';
            const overlay = document.querySelector('.overlay');
            overlay.style.display = 'none';
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
});
