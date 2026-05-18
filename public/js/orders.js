function toggleDetails(orderId)
{
    const details = document.getElementById(`details-${orderId}`);

    if (details.style.display === 'block') {
        details.style.display = 'none';
    }
    else {
        details.style.display = 'block';
    }
}