// JavaScript function to handle order status updates
function updateStatus(status, orderId) {
    if (status === 'accept') {
        if (confirm('Are you sure you want to accept this order?')) {
            // Send a request to the server to update the order status
            window.location.href = `update_order_status.php?status=accept&order_id=${orderId}`;
        }
    } else if (status === 'delivered') {
        if (confirm('Are you sure this order is delivered?')) {
            // Send a request to the server to update the order status
            window.location.href = `update_order_status.php?status=delivered&order_id=${orderId}`;
        }
    }
}

function updateStatus(button, status, orderId) {
    fetch('../PHP/update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${status}`
        })
        .then(res => res.text())
        .then(response => {
            if (status === 'accept') {
                button.classList.remove('btn-warning');
                button.classList.add('btn-secondary');
                button.innerText = "Packing";
            } else if (status === 'delivered') {
                button.classList.remove('btn-success');
                button.classList.add('btn-dark');
                button.innerText = "Out for Delivery";
            }
            button.disabled = true;
        })
        .catch(err => {
            alert("Error updating order status");
            console.error(err);
        });
}