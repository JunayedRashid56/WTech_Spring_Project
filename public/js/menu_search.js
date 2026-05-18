/* Category-aware fallback images (mirrors PHP menuFallbackImg) */
function getFallbackImg(cat) {
    const map = {
        burger:  'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=75',
        pizza:   'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&q=75',
        noodle:  'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=500&q=75',
        pasta:   'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=500&q=75',
        chicken: 'https://images.unsplash.com/photo-1562967914-608f82629710?w=500&q=75',
        rice:    'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=500&q=75',
        dessert: 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&q=75',
        cake:    'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&q=75',
        salad:   'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=75',
        healthy: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=75',
        soup:    'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&q=75',
        bbq:     'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500&q=75',
        seafood: 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=500&q=75',
        drink:   'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=75',
        ice:     'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=500&q=75',
        snack:   'https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=500&q=75',
    };
    const low = (cat || '').toLowerCase();
    for (const [key, url] of Object.entries(map)) {
        if (low.includes(key)) return url;
    }
    return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=75';
}

const searchInput  = document.getElementById('searchInput');
const menuContainer = document.getElementById('menuContainer');
const resultsLabel  = document.getElementById('resultsLabel');

searchInput.addEventListener('keyup', function () {
    const query = searchInput.value.trim();

    fetch(`/WTech Project/api/menu_items/search.php?q=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(data => {
            menuContainer.innerHTML = '';

            if (!data.items || data.items.length === 0) {
                menuContainer.innerHTML = `
                    <div class="mn-empty">
                        <div class="mn-empty-icon">🔍</div>
                        <h3>No results for "${query}"</h3>
                        <p>Try a different name or browse by category.</p>
                    </div>`;
                if (resultsLabel) resultsLabel.innerHTML = 'Showing <strong>0</strong> items';
                return;
            }

            if (resultsLabel) {
                const n = data.items.length;
                resultsLabel.innerHTML = `Showing <strong>${n}</strong> item${n !== 1 ? 's' : ''}`;
            }

            data.items.forEach(item => {
                const imgSrc = item.image_path
                    ? `/WTech Project/public/uploads/menu/${item.image_path}`
                    : getFallbackImg(item.category_name);

                const price = parseFloat(item.price).toFixed(0);
                const available = parseInt(item.is_available) !== 0;

                const addBtn = available
                    ? `<button class="mn-add-btn add-to-cart" data-id="${item.id}">+ Add</button>`
                    : `<button class="mn-add-btn" disabled style="background:#ccc;box-shadow:none;cursor:not-allowed;">Sold Out</button>`;

                menuContainer.innerHTML += `
                    <div class="mn-card" data-cat="${item.category_name}">
                        <div class="mn-card-img">
                            <img src="${imgSrc}" alt="${item.name}"
                                 loading="lazy"
                                 onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=70'">
                            <span class="mn-card-badge">${item.category_name}</span>
                        </div>
                        <div class="mn-card-body">
                            <h3>${item.name}</h3>
                            <p>${item.description}</p>
                            <div class="mn-card-footer">
                                <div class="mn-card-price">৳${price} <span>BDT</span></div>
                                ${addBtn}
                            </div>
                        </div>
                    </div>`;
            });

            /* Re-attach cart listeners on new buttons */
            menuContainer.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', function () {
                    const itemId = this.dataset.id;
                    this.textContent = '…';
                    this.disabled = true;
                    fetch('/WTech Project/api/cart/add.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `item_id=${itemId}`
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            const badge = document.getElementById('cart-count');
                            if (badge) badge.textContent = d.cart_count;
                            this.textContent = '✓ Added';
                            setTimeout(() => {
                                this.textContent = '+ Add';
                                this.disabled = false;
                            }, 1400);
                        } else {
                            this.textContent = '+ Add';
                            this.disabled = false;
                        }
                    });
                });
            });
        })
        .catch(err => console.error(err));
});