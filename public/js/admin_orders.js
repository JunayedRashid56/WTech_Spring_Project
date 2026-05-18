async function updateStatus(orderId, status)
{
    if (!status) {
        return;
    }

    try {

        const response = await fetch(
            '/WTech Project/api/orders/update_status.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    order_id: orderId,
                    status: status
                })
            }
        );

        const data = await response.json();

        if (data.ok) {

            const badge = document.getElementById(
                `badge-${orderId}`
            );

            badge.innerText = data.status;

            badge.className = 'status-badge';

            badge.classList.add(
                data.status.replace(/\s/g, '-')
            );
        }
        else {

            alert(data.message);
        }

    }
    catch(error) {

        console.log(error);
    }
}