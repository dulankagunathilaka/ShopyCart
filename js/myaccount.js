// Enable profile editing by making input fields editable and toggling buttons
function editProfile() {
    document.querySelectorAll('.form-control').forEach(input => {
        input.removeAttribute('readonly');
    });
    document.getElementById("editButton").style.display = "none";
    document.getElementById("saveButton").style.display = "inline-block";
}

// Handle profile image preview and upload when a new file is selected
document.getElementById("fileInput").addEventListener("change", function() {
    if (this.files && this.files[0]) {
        // Preview selected image
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("profileImage").src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);

        // Upload image to the server
        let formData = new FormData();
        formData.append("profilePic", this.files[0]);

        fetch("", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => alert(data))
            .catch(error => console.error("Error uploading image:", error));
    }
});
