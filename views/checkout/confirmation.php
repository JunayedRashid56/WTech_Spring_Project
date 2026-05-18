<?php
session_start();

if (!isset($_SESSION['last_order_id'])) {
    header('Location: /WTech Project/routes/web.php');
    exit;
}
$orderId    = $_SESSION['last_order_id'];
$orderItems = $_SESSION['last_order_items'] ?? [];
$orderTotal = $_SESSION['last_order_total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .cf-page {
            max-width: 620px;
            margin: 0 auto;
            padding: 48px 24px 60px;
            text-align: center;
        }

        /* Progress steps */
        .co-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 40px;
        }
        .co-step { display: flex; align-items: center; gap: 8px; font-size: .85rem; font-weight: 600; color: #aaa; }
        .co-step-num {
            width: 28px; height: 28px; border-radius: 50%;
            background: #dde3f0; color: #aaa;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 800;
        }
        .co-step.done .co-step-num { background: #1565c0; color: white; }
        .co-step.done              { color: #1565c0; }
        .co-step-line { width: 60px; height: 2px; background: #1565c0; margin: 0 6px; }

        /* Success icon */
        .cf-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.6rem;
            margin: 0 auto 24px;
            box-shadow: 0 8px 28px rgba(21,101,192,.35);
            animation: cf-pop .4s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes cf-pop {
            from { transform: scale(0); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        .cf-title {
            font-size: 1.9rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 8px;
        }
        .cf-subtitle {
            color: #666;
            font-size: .97rem;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        /* Order ID badge */
        .cf-order-id {
            display: inline-block;
            background: #e8f0fe;
            color: #1565c0;
            font-size: .85rem;
            font-weight: 800;
            padding: 7px 18px;
            border-radius: 20px;
            margin-bottom: 28px;
            letter-spacing: .5px;
        }

        /* Items card */
        .cf-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 24px 24px;
            text-align: left;
            margin-bottom: 20px;
        }
        .cf-card-title {
            font-size: .9rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f2f5;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .cf-item {
            display: flex;
            justify-content: space-between;
            font-size: .92rem;
            color: #444;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .cf-item:last-child { border-bottom: none; }
        .cf-item-name { font-weight: 600; }
        .cf-item-qty  { color: #999; font-size: .82rem; margin-top: 2px; }
        .cf-item-price { font-weight: 800; color: #0a0a0a; }

        .cf-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 2px solid #f0f2f5;
        }
        .cf-total-row span:last-child { color: #1565c0; }

        /* CTAs */
        .cf-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 28px;
        }
        .cf-btn-primary {
            padding: 13px 28px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-weight: 700;
            font-size: .95rem;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(21,101,192,.3);
            transition: transform .15s;
        }
        .cf-btn-primary:hover { transform: translateY(-2px); }
        .cf-btn-outline {
            padding: 13px 28px;
            background: white;
            color: #1565c0;
            font-weight: 700;
            font-size: .95rem;
            border-radius: 10px;
            text-decoration: none;
            border: 2px solid #1565c0;
            transition: background .15s, color .15s;
        }
        .cf-btn-outline:hover { background: #e8f0fe; }

        /* Delivery note */
        .cf-note {
            font-size: .82rem;
            color: #aaa;
            margin-top: 22px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="cf-page">

    <!-- Progress -->
    <div class="co-steps">
        <div class="co-step done">
            <div class="co-step-num">✓</div>
            <span>Cart</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step done">
            <div class="co-step-num">✓</div>
            <span>Checkout</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step done">
            <div class="co-step-num">✓</div>
            <span>Confirmed</span>
        </div>
    </div>

    <!-- Success -->
    <div class="cf-icon">🎉</div>
    <div class="cf-title">Order Placed!</div>
    <p class="cf-subtitle">
        Thank you for your order.<br>
        We're preparing your food and will deliver it shortly.
    </p>

    <div class="cf-order-id">Order #<?= htmlspecialchars($orderId) ?></div>

    <!-- Items -->
    <div class="cf-card">
        <div class="cf-card-title">Your Items</div>
        <?php foreach ($orderItems as $item): ?>
        <div class="cf-item">
            <div>
                <div class="cf-item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="cf-item-qty">× <?= $item['quantity'] ?></div>
            </div>
            <div class="cf-item-price">৳<?= number_format($item['price'] * $item['quantity'], 0) ?></div>
        </div>
        <?php endforeach; ?>

        <div class="cf-total-row">
            <span>Total Paid</span>
            <span>৳<?= number_format($orderTotal, 0) ?></span>
        </div>
    </div>

    <!-- CTAs -->
    <div class="cf-btns">
        <a href="/WTech Project/routes/my_orders.php" class="cf-btn-primary">📦 Track My Order</a>
        <a href="/WTech Project/routes/web.php" class="cf-btn-outline">Continue Shopping</a>
    </div>

    <p class="cf-note">
        🚚 Estimated delivery: 25–40 minutes<br>
        Questions? Contact our support team.
    </p>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>