const buttons = document.querySelectorAll('.toggleBtn');

buttons.forEach(button => {

    button.addEventListener('click', function () {

        const id = this.dataset.id;

        fetch('/WTech Project/api/toggle.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({id: id})
        })
        .then(response => response.json())
        .then(data => {

            if (data.ok) {
                this.innerText = data.is_available ? 'Active' : 'Inactive';
                this.className = 'toggleBtn ' + (data.is_available ? 'badge-active' : 'badge-inactive');
            }

        });

    });

});