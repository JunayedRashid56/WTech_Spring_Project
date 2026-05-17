const searchInput = document.getElementById('searchInput');
const menuContainer = document.getElementById('menuContainer');

searchInput.addEventListener('keyup', function () {

    const query = searchInput.value;

    fetch(`../../api/menu-items/search.php?q=${query}`)
        .then(response => response.json())
        .then(data => {

            menuContainer.innerHTML = '';

            if (data.items.length === 0) {
                menuContainer.innerHTML = '<h3>No items found</h3>';
                return;
            }

            data.items.forEach(item => {

                menuContainer.innerHTML += `

                    <div class="menu-card">

                        <img
                            src="../../public/uploads/menu/${item.image_path}"
                            alt="Food"
                        >

                        <h3>${item.name}</h3>

                        <p>${item.description}</p>

                        <p>Category: ${item.category_name}</p>

                        <h4>৳${item.price}</h4>

                        <button>Add to Cart</button>

                    </div>
                `;
            });
        })
        .catch(error => {
            console.log(error);
        });
});