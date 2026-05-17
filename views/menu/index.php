<!DOCTYPE html>
<html>

<head>
    <title>Menu</title>

    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <h1>Restaurant Menu</h1>

    <div class="search-box">
        <input
            type="text"
            id="searchInput"
            placeholder="Search food items...">
    </div>

    <div id="menuContainer" class="menu-container">

        <?php
        // Ensure $items is defined as an array if not already set
        if (!isset($items) || !is_array($items)) {
            $items = [];
        }
        foreach ($items as $item): ?>

            <div class="menu-card">

                <img
                    src="../../public/uploads/menu/<?php echo $item['image_path']; ?>"
                    alt="Food Image">

                <h3><?php echo htmlspecialchars($item['name']); ?></h3>

                <p>
                    <?php echo htmlspecialchars($item['description']); ?>
                </p>

                <p>
                    Category:
                    <?php echo htmlspecialchars($item['category_name']); ?>
                </p>

                <h4>
                    ৳<?php echo number_format($item['price'], 2); ?>
                </h4>

                <button class="add-to-cart" data-id="<?php echo $item['id']; ?>">
                    Add to Cart
                </button>

            </div>

        <?php endforeach; ?>

    </div>

    <script src="../../public/js/menu-search.js"></script>

</body>

</html>