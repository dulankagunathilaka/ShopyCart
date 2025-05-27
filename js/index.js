//Toggle Script
function toggleForgotPassword() {
    var section = document.getElementById("forgotPasswordSection");
    section.style.display = section.style.display === "none" ? "block" : "none";
}

//Cart option message before sign in 
function showLoginMessage() {
    alert("Please sign in to shop.");
}

window.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const searchTerm = params.get('search');

    if (searchTerm && searchTerm.trim() !== '') {
        const freshFindsSection = document.getElementById('fresh-finds');
        if (freshFindsSection) {
            freshFindsSection.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }
});


