// Function to handle the Add to Cart functionality
function addToCart(productId) {

    // Get the form using the product ID
    const form = document.getElementById('addToCartForm' + productId);

    // Create a FormData object to capture form data
    const formData = new FormData(form);

    // Use AJAX to send the form data to the PHP script without reloading the page
    fetch('../PHP/add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'Item added to cart') {

                // Show the success message once the item is added to the cart
                showSuccessMessage();
            } else {
                
                // Handle errors, if any
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Function to show a pop-up success message at the center with transparent background
function showSuccessMessage() {
    
    // Create a div element for the pop-up
    const successMessage = document.createElement('div');
    successMessage.textContent = 'Item added successfully!';

    // Style the success message
    successMessage.style.position = 'fixed';
    successMessage.style.top = '50%';
    successMessage.style.left = '50%';
    successMessage.style.transform = 'translate(-50%, -50%)'; // Center the message
    successMessage.style.padding = '10px 20px'; // Smaller padding for a smaller message
    successMessage.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // More transparent black background
    successMessage.style.color = 'white';
    successMessage.style.borderRadius = '8px'; // Smaller border radius
    successMessage.style.fontSize = '14px'; // Smaller font size
    successMessage.style.zIndex = '9999';
    successMessage.style.textAlign = 'center';
    successMessage.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.3)'; // Optional: Add shadow for better visibility
    successMessage.style.opacity = '1'; // Initially fully visible

    // Append the success message to the body
    document.body.appendChild(successMessage);

    // Add the animation to shrink and fade out
    successMessage.style.animation = 'shrinkAndFade 3s forwards'; // Apply animation

    // Remove the message after the animation completes (3 seconds)
    setTimeout(() => {
        successMessage.remove();
    }, 3000);
}