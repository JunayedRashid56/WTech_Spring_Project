<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .ord-page {
            max-width: 780px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }
        .ord-page-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 6px;
        }
        .ord-page-sub {
            font-size: .92rem;
            color: #888;
            margin-bottom: 32px;
        }

        /* Order card */
        .order-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            margin-bottom: 16px;
            overflow: hidden;
            transition: box-shadow .2s;
        }
        .order-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.11); }

        /* Card header — always visible */
        .order-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            cursor: pointer;
            gap: 16px;
        }
        .ord-left { flex: 1; min-width: 0; }
        .ord-order-num {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 4px;
        }
        .ord-meta {
            font-size: .82rem;
            color: #999;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .ord-meta span { display: flex; align-items: center; gap: 4px; }

        .ord-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }
        .ord-total {
            font-size: 1.1rem;
            font-weight: 900;
            color: #0a0a0a;
        }

        /* Status badge */
        .status {
            display: inline-block;
            padding: 5px 13px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .Pending         { background: #fff3e0; color: #e65100; }
        .Preparing       { background: #e3f2fd; color: #0d47a1; }
        .Out-for-Delivery { background: #f3e5f5; color: #6a1b9a; }
        .Delivered       { background: #e8f5e9; color: #1b5e20; }
        .Cancelled       { background: #fce4ec; color: #880e4f; }

        /* Chevron toggle */
        .ord-chevron {
            font-size: .75rem;
            color: #bbb;
            transition: transform .2s;
            margin-left: 8px;
        }
        .order-card.open .ord-chevron { transform: rotate(180deg); }

        /* Track link */
        .ord-track-btn {
            font-size: .8rem;
            font-weight: 700;
            color: #1565c0;
            text-decoration: none;
            padding: 5px 12px;
            border: 2px solid #1565c0;
            border-radius: 20px;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .ord-track-btn:hover { background: #1565c0; color: white; }

        /* Expanded items section */
        .order-details {
            display: none;
            border-top: 2px solid #f0f2f5;
            padding: 16px 22px 20px;
        }
        .order-card.open .order-details { display: block; }

        .ord-items-title {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #aaa;
            margin-bottom: 12px;
        }
        .ord-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: .9rem;
            color: #333;
        }
        .ord-item-row:last-child { border-bottom: none; }
        .ord-item-name { font-weight: 600; }
        .ord-item-qty  { color: #999; font-size: .82rem; margin-left: 6px; }
        .ord-item-price { font-weight: 700; color: #0a0a0a; }

        /* Empty state */
        .ord-empty {
            text-align: center;
            padding: 72px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .ord-empty-icon { font-size: 3.5rem; margin-bottom: 16px; }
        .ord-empty h3 { font-size: 1.2rem; font-weight: 800; color: #0a0a0a; margin-bottom: 8px; }
        .ord-empty p  { color: #777; font-size: .92rem; margin-bottom: 26px; }
        .ord-empty-btn {
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
        .ord-empty-btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="ord-page">

    <div class="ord-page-title">📦 My Orders</div>
    <div class="ord-page-sub">Click on any order to see items · Use Track to follow live status</div>

    <?php if (empty($orders)): ?>

    <div class="ord-empty">
        <div class="ord-empty-icon">🛍️</div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Browse our menu and order something delicious!</p>
        <a href="/WTech Project/routes/web.php" class="ord-empty-btn">Browse Menu</a>
    </div>

    <?php else: ?>

        <?php foreach ($orders as $order):
            $statusClass = str_replace(' ', '-', $order['status']);
            $orderItems  = $orderModel->getOrderItems($order['id']);
            $date        = date('d M Y, h:i A', strtotime($order['created_at']));
        ?>
        <div class="order-card" id="card-<?= $order['id'] ?>">

            <!-- Header row -->
            <div class="order-header" onclick="toggleDetails(<?= $order['id'] ?>)">
                <div class="ord-left">
                    <div class="ord-order-num">Order #<?= $order['id'] ?></div>
                    <div class="ord-meta">
                        <span>🕐 <?= $date ?></span>
                        <span>🍽️ <?= $order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?></span>
                    </div>
                </div>

                <div class="ord-right">
                    <span class="status <?= $statusClass ?>"><?= htmlspecialchars($order['status']) ?></span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span class="ord-total">৳<?= number_format($order['total_amount'], 0) ?></span>
                        <a href="/WTech Project/views/orders/order_details.php?id=<?= $order['id'] ?>"
                           class="ord-track-btn"
                           onclick="event.stopPropagation()">Track</a>
                        <span class="ord-chevron">▼</span>
                    </div>
                </div>
            </div>

            <!-- Expandable items -->
            <div class="order-details" id="details-<?= $order['id'] ?>">
                <div class="ord-items-title">Items in this order</div>
                <?php foreach ($orderItems as $item): ?>
                <div class="ord-item-row">
                    <span>
                        <span class="ord-item-name"><?= htmlspecialchars($item['name']) ?></span>
                        <span class="ord-item-qty">× <?= $item['quantity'] ?></span>
                    </span>
                    <span class="ord-item-price">৳<?= number_format($item['unit_price'] * $item['quantity'], 0) ?></span>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script>
function toggleDetails(id) {
    const card = document.getElementById('card-' + id);
    card.classList.toggle('open');
}
</script>

</body>
</html>