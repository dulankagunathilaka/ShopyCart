function editProfile() {
    // Enable text inputs
    document.getElementById('full_name').removeAttribute('readonly');
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('address').removeAttribute('readonly');

    // Enable the file input
    const fileInput = document.getElementById('fileInput');
    fileInput.removeAttribute('disabled');

    // Enable clicking on the profile picture edit label
    const editPicLabel = document.getElementById('editPicLabel');
    if (editPicLabel) {
        editPicLabel.style.pointerEvents = 'auto';
        editPicLabel.style.opacity = '1';
        editPicLabel.style.cursor = 'pointer';
    }

    // Hide Edit button and show Save button at the same place
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');

    editButton.style.display = 'none';
    saveButton.style.display = 'inline-block';

    // Optionally disable edit button to prevent multiple clicks
    editButton.disabled = true;
}

function cancelEdit() {
    // Disable text inputs again
    document.getElementById('full_name').setAttribute('readonly', true);
    document.getElementById('email').setAttribute('readonly', true);
    document.getElementById('address').setAttribute('readonly', true);

    // Disable the file input
    const fileInput = document.getElementById('fileInput');
    fileInput.value = ""; // Clear any selected file
    fileInput.setAttribute('disabled', true);

    // Disable clicking on the profile picture edit label
    const editPicLabel = document.getElementById('editPicLabel');
    if (editPicLabel) {
        editPicLabel.style.pointerEvents = 'none';
        editPicLabel.style.opacity = '0.5';
        editPicLabel.style.cursor = 'default';
    }

    // Hide Save button and show Edit button at the same place
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');

    saveButton.style.display = 'none';
    editButton.style.display = 'inline-block';

    editButton.disabled = false;

    // Optionally, reset profile image preview to original
    const profileImage = document.getElementById('profileImage');
    if (profileImage && profileImage.dataset.originalSrc) {
        profileImage.src = profileImage.dataset.originalSrc;
    }
}

// Image preview when user selects a new profile image
document.getElementById('fileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('profileImage').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});
