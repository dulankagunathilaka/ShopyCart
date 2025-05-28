// Update total price when quantity changes
function updateTotalPrice(productId, unitPrice) {
    const qtyInput = document.getElementById('quantity' + productId);
    const totalDisplay = document.getElementById('totalPrice' + productId);
    const quantity = parseInt(qtyInput.value) || 1;
    const total = quantity * unitPrice;
    totalDisplay.textContent = "Total: Rs. " + total;
}

// Add product to cart using x-www-form-urlencoded format
function addToCart(productId) {
    const quantity = document.getElementById('quantity' + productId).value || 1;
    const bodyData = `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`;

    fetch('../PHP/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: bodyData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'Item added to cart') {
            showSuccessMessage();
        } else {
            console.error('Server error:', data);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
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



    document.body.appendChild(successMessage);

    setTimeout(() => {
        successMessage.remove();
    }, 3000);
}