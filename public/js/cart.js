const addButtons = document.querySelectorAll('.add-to-cart');

addButtons.forEach(button => {

    button.addEventListener('click', function () {

        const itemId = this.dataset.id;

        fetch('/WTech Project/api/cart/add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) cartCount.innerText = data.cart_count;

                const orig = this.textContent;
                this.textContent = '✓ Added';
                this.disabled = true;
                setTimeout(() => {
                    this.textContent = orig;
                    this.disabled = false;
                }, 1400);
            }
        });
    });
});

const quantityButtons = document.querySelectorAll('.quantity-btn');

quantityButtons.forEach(button => {

    button.addEventListener('click', function () {

        const itemId = this.dataset.id;
        const action = this.dataset.action;

        const qtyElement = document.getElementById(`qty-${itemId}`);

        let quantity = parseInt(qtyElement.innerText);

        if (action === 'plus') {
            quantity++;
        } else {
            quantity--;
        }

        fetch('/WTech Project/api/cart/update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_id=${itemId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                if (quantity <= 0) {

                    document.getElementById(`row-${itemId}`).remove();

                } else {

                    qtyElement.innerText = quantity;

                    document.getElementById(`total-${itemId}`).innerText =
                        data.line_total.toFixed(2);
                }

                document.getElementById('grandTotal').innerText =
                    data.grand_total.toFixed(2);
            }
        });
    });
});

const removeButtons = document.querySelectorAll('.remove-btn');

removeButtons.forEach(button => {

    button.addEventListener('click', function () {

        const itemId = this.dataset.id;

        fetch('/WTech Project/api/cart/remove.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                document.getElementById(`row-${itemId}`).remove();

                document.getElementById('grandTotal').innerText =
                    data.grand_total.toFixed(2);
            }
        });
    });
});