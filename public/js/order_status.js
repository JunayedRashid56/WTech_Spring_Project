const statusBadge = document.getElementById('statusBadge');

let polling = setInterval(checkOrderStatus, 10000);

async function checkOrderStatus()
{
    try {

        const response = await fetch(
            `/WTech Project/api/orders/status.php?id=${orderId}`
        );

        const data = await response.json();

        if (data.ok) {

            updateStatusBadge(data.status);
        } else {

            console.error('Failed to fetch order status:', data.error);
        }
    } catch (error) {

        console.error('Error fetching order status:', error);
    }
}

function updateStatusBadge(status)
{
    statusBadge.textContent = status;
    statusBadge.className = 'status-badge ' + status.replace(' ', '-');
    statusBadge.style.display = 'inline-block';
    if (status === 'Delivered' || status === 'Cancelled') {
        clearInterval(polling);
    }
}