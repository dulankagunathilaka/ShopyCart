// Enable profile editing by making input fields editable and toggling buttons
function editProfile() {
    document.querySelectorAll('.form-control').forEach(input => {
        input.removeAttribute('readonly');
    });
    document.getElementById("editButton").style.display = "none";
    document.getElementById("saveButton").style.display = "inline-block";
}

