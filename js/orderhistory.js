// Function to update the status of an order and update the button UI accordingly
function updateStatus(button, status, orderId) {
    // Send a POST request to the server to update order status
    fetch('../PHP/update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `order_id=${orderId}&status=${status}`
        })
        .then(res => res.text()) // Convert server response to text
        .then(response => {
            // Update button appearance and label based on new status
            if (status === 'accept') {
                button.classList.remove('btn-warning');
                button.classList.add('btn-secondary');
                button.innerText = "Packing";
            } else if (status === 'delivered') {
                button.classList.remove('btn-success');
                button.classList.add('btn-dark');
                button.innerText = "Out for Delivery";
            }

            // Disable the button to prevent further clicks
            button.disabled = true;
        })
        .catch(err => {
            // Handle any errors during the request
            alert("Error updating order status");
            console.error(err);
        });
}
