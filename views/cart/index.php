<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cartFallbackImg(string $name): string {
    $map = [
        'burger'  => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&q=70',
        'pizza'   => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&q=70',
        'noodle'  => 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=200&q=70',
        'chicken' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=200&q=70',
        'rice'    => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=200&q=70',
        'dessert' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=200&q=70',
        'cake'    => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=200&q=70',
        'salad'   => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200&q=70',
        'fries'   => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=200&q=70',
        'drink'   => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=200&q=70',
        'cola'    => 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=200&q=70',
        'soup'    => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=200&q=70',
    ];
    $low = strtolower($name);
    foreach ($map as $key => $url) {
        if (str_contains($low, $key)) return $url;
    }
    return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200&q=70';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .cart-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }

        /* Page title */
        .cart-page-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cart-page-title span {
            font-size: 1rem;
            font-weight: 500;
            color: #888;
        }

        /* Two-column layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }
        @media (max-width: 820px) {
            .cart-layout { grid-template-columns: 1fr; }
        }

        /* ---- ITEM CARDS ---- */
        .cart-item {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            padding: 18px 20px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: box-shadow .2s;
        }
        .cart-item:hover { box-shadow: 0 6px 24px rgba(0,0,0,.11); }

        .cart-item-img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: #e8f0fe;
        }

        .cart-item-info { flex: 1; min-width: 0; }
        .cart-item-info h3 {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-price {
            font-size: .88rem;
            color: #666;
            margin-bottom: 0;
        }

        /* Qty controls */
        .cart-qty-wrap {
            display: flex;
            align-items: center;
            gap: 0;
            border: 2px solid #dde3f0;
            border-radius: 25px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .quantity-btn {
            background: white;
            border: none;
            width: 34px;
            height: 34px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            color: #1565c0;
            transition: background .15s;
            line-height: 1;
        }
        .quantity-btn:hover { background: #e8f0fe; }
        .cart-qty-num {
            min-width: 32px;
            text-align: center;
            font-size: .95rem;
            font-weight: 700;
            color: #0a0a0a;
            border-left: 1px solid #dde3f0;
            border-right: 1px solid #dde3f0;
            padding: 0 2px;
            line-height: 34px;
        }

        /* Line total */
        .cart-line-total {
            font-size: 1.05rem;
            font-weight: 900;
            color: #0a0a0a;
            min-width: 72px;
            text-align: right;
            flex-shrink: 0;
        }

        /* Remove btn */
        .remove-btn {
            background: none;
            border: none;
            color: #ccc;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 6px;
            transition: color .15s, background .15s;
            flex-shrink: 0;
            line-height: 1;
        }
        .remove-btn:hover { color: #c62828; background: #fdecea; }

        /* ---- ORDER SUMMARY PANEL ---- */
        .cart-summary {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,.08);
            padding: 24px 22px;
            position: sticky;
            top: 88px;
        }
        .cart-summary h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #f0f2f5;
        }
        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            font-size: .92rem;
            color: #555;
            margin-bottom: 12px;
        }
        .cart-summary-divider {
            height: 1px;
            background: #f0f2f5;
            margin: 14px 0;
        }
        .cart-summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.15rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 22px;
        }
        .cart-summary-total span:last-child { color: #1565c0; }

        .cart-checkout-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 16px rgba(21,101,192,.35);
            letter-spacing: .3px;
        }
        .cart-checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(21,101,192,.45);
        }

        .cart-continue-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: .88rem;
            color: #1565c0;
            font-weight: 600;
            text-decoration: none;
        }
        .cart-continue-link:hover { text-decoration: underline; }

        /* ---- EMPTY STATE ---- */
        .cart-empty {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .cart-empty-icon { font-size: 4rem; margin-bottom: 16px; }
        .cart-empty h3 { font-size: 1.3rem; font-weight: 800; color: #0a0a0a; margin-bottom: 8px; }
        .cart-empty p  { color: #777; font-size: .95rem; margin-bottom: 28px; }
        .cart-empty-btn {
            display: inline-block;
            padding: 13px 32px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(21,101,192,.3);
            transition: transform .15s;
        }
        .cart-empty-btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="cart-page">

    <div class="cart-page-title">
        🛒 My Cart
        <?php if (!empty($cartItems)): ?>
            <span><?= count($cartItems) ?> item<?= count($cartItems) !== 1 ? 's' : '' ?></span>
        <?php endif; ?>
    </div>

    <?php if (!empty($cartItems)): ?>

    <div class="cart-layout">

        <!-- ===== LEFT: ITEMS ===== -->
        <div id="cartContainer">
            <?php foreach ($cartItems as $item):
                $imgSrc = !empty($item['image_path'])
                    ? '/WTech Project/public/uploads/menu/' . htmlspecialchars($item['image_path'])
                    : cartFallbackImg($item['name']);
            ?>
            <div class="cart-item" id="row-<?= $item['id'] ?>">

                <img class="cart-item-img"
                     src="<?= $imgSrc ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=200&q=70'">

                <div class="cart-item-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="cart-item-price">৳<?= number_format($item['price'], 0) ?> each</p>
                </div>

                <!-- Qty controls -->
                <div class="cart-qty-wrap">
                    <button class="quantity-btn"
                            data-id="<?= $item['id'] ?>"
                            data-action="minus">−</button>
                    <span class="cart-qty-num" id="qty-<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                    <button class="quantity-btn"
                            data-id="<?= $item['id'] ?>"
                            data-action="plus">+</button>
                </div>

                <!-- Line total -->
                <div class="cart-line-total">
                    ৳<span id="total-<?= $item['id'] ?>"><?= number_format($item['total'], 0) ?></span>
                </div>

                <!-- Remove -->
                <button class="remove-btn" data-id="<?= $item['id'] ?>" title="Remove">✕</button>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- ===== RIGHT: SUMMARY ===== -->
        <div class="cart-summary">
            <h3>Order Summary</h3>

            <?php foreach ($cartItems as $item): ?>
            <div class="cart-summary-row">
                <span><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                <span>৳<?= number_format($item['total'], 0) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="cart-summary-divider"></div>

            <div class="cart-summary-row">
                <span>Subtotal</span>
                <span>৳<?= number_format($grandTotal, 0) ?></span>
            </div>
            <div class="cart-summary-row">
                <span>Delivery fee</span>
                <span style="color:#1e7e34;font-weight:700;">FREE</span>
            </div>

            <div class="cart-summary-divider"></div>

            <div class="cart-summary-total">
                <span>Total</span>
                <span>৳<span id="grandTotal"><?= number_format($grandTotal, 0) ?></span></span>
            </div>

            <a href="/WTech Project/routes/checkout.php" class="cart-checkout-btn">
                Proceed to Checkout →
            </a>

            <a href="/WTech Project/routes/web.php" class="cart-continue-link">
                ← Continue Shopping
            </a>
        </div>

    </div>

    <?php else: ?>

    <!-- Empty state -->
    <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added anything yet.<br>Browse our menu and find something delicious!</p>
        <a href="/WTech Project/routes/web.php" class="cart-empty-btn">Browse Menu</a>
    </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script src="/WTech Project/public/js/cart.js"></script>

</body>
</html>