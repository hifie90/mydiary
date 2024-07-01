document.addEventListener('DOMContentLoaded', function () {
    var dropbtns = document.querySelectorAll('.dropbtn');
    
    dropbtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevents the click event from bubbling up to window
            closeAllDropdowns(); // Close all other dropdowns
            this.nextElementSibling.classList.toggle('show');
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.addEventListener('click', function () {
        closeAllDropdowns();
    });

    function closeAllDropdowns() {
        var dropdowns = document.querySelectorAll('.dropdown-content');
        dropdowns.forEach(function (dropdown) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    }

    var editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var id = this.dataset.id;
            var titleElement = document.querySelector('.card-title[data-id="' + id + '"]');
            var descriptionElement = document.querySelector('.card-text[data-id="' + id + '"]');

            titleElement.setAttribute('contenteditable', 'true');
            descriptionElement.setAttribute('contenteditable', 'true');

            titleElement.focus();

            titleElement.addEventListener('blur', function() {
                this.setAttribute('contenteditable', 'false');
                // Save the updated title
                saveChanges(id, 'title', this.innerText);
            });

            descriptionElement.addEventListener('blur', function() {
                this.setAttribute('contenteditable', 'false');
                // Save the updated description
                saveChanges(id, 'description', this.innerText);
            });
        });
    });


    function saveChanges(id, field, value) {
        // Make an AJAX request to save the changes
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + id + '&field=' + field + '&value=' + encodeURIComponent(value));

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Changes saved successfully.');
            } else {
                console.log('Error saving changes.');
            }
        };
    }



});