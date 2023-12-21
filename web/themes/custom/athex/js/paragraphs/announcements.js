document.getElementById('selectAll').addEventListener('change', function () {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
      checkbox.checked = event.target.checked;
    });

    updateDownloadButtonState();
  });

  document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener('change', updateDownloadButtonState);
    });
  });

  function updateDownloadButtonState() {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    var downloadButton = document.getElementById('downloadButton');
    
    var atLeastOneCheckboxChecked = Array.from(checkboxes).some(function (checkbox) {
      return checkbox.checked;
    });

    downloadButton.style.opacity = atLeastOneCheckboxChecked ? '1' : '0.2';
    downloadButton.disabled = !atLeastOneCheckboxChecked;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        updateRowBackground(checkbox);
        updateDownloadButtonState();
      });
    });
  });
  
  function updateRowBackground(checkbox) {
    var row = checkbox.parentNode.parentNode;
    if (checkbox.checked) {
      row.classList.add('selected-row');
    } else {
      row.classList.remove('selected-row');
    }
  }
  
  function updateDownloadButtonState() {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    var downloadButton = document.getElementById('downloadButton');
  
    var atLeastOneCheckboxChecked = Array.from(checkboxes).some(function (checkbox) {
      return checkbox.checked;
    });
  
    downloadButton.style.opacity = atLeastOneCheckboxChecked ? '1' : '0.2';
    downloadButton.disabled = !atLeastOneCheckboxChecked;
  }
  

  document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        updateRowBackground(checkbox);
        updateDownloadButtonState();
        updateSelectAllBackground();
      });
    });
  
    document.getElementById('selectAll').addEventListener('change', function () {
      checkboxes.forEach(function (checkbox) {
        checkbox.checked = event.target.checked;
        updateRowBackground(checkbox);
      });
  
      updateDownloadButtonState();
      updateSelectAllBackground();
    });
  });
  
  function updateRowBackground(checkbox) {
    var row = checkbox.parentNode.parentNode;
    if (checkbox.checked) {
      row.classList.add('selected-row');
    } else {
      row.classList.remove('selected-row');
    }
  }
  
  function updateDownloadButtonState() {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    var downloadButton = document.getElementById('downloadButton');
  
    var atLeastOneCheckboxChecked = Array.from(checkboxes).some(function (checkbox) {
      return checkbox.checked;
    });
  
    downloadButton.style.opacity = atLeastOneCheckboxChecked ? '1' : '0.2';
    downloadButton.disabled = !atLeastOneCheckboxChecked;
  }
  
  function updateSelectAllBackground() {
    var selectAllCheckbox = document.getElementById('selectAll');
    var allCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    var allCheckboxesChecked = Array.from(allCheckboxes).every(function (checkbox) {
      return checkbox.checked;
    });
  
    if (allCheckboxesChecked) {
      selectAllCheckbox.parentNode.parentNode.classList.add('selected-row');
    } else {
      selectAllCheckbox.parentNode.parentNode.classList.remove('selected-row');
    }
  }
  function toggleDropdown() {
    var dropdown = document.getElementById('myDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
  }  

  function toggleDropdown() {
    var dropdown = document.getElementById('myDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
  }

  document.addEventListener('click', function(event) {
    var dropdown = document.getElementById('myDropdown');
    var button = document.querySelector('.btn-group .btn');

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.style.display = 'none';
    }
  });

  
  function handleDropdownOptionClick(option) {
    var dropdown = document.getElementById('myDropdown');
    dropdown.style.display = 'none';
  }


  