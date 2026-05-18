<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .co-page {
            max-width: 1060px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }

        /* Progress steps */
        .co-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 36px;
        }
        .co-step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            font-weight: 600;
            color: #aaa;
        }
        .co-step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #dde3f0;
            color: #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 800;
        }
        .co-step.done .co-step-num  { background: #1565c0; color: white; }
        .co-step.done               { color: #1565c0; }
        .co-step.active .co-step-num { background: #0d47a1; color: white; }
        .co-step.active              { color: #0a0a0a; }
        .co-step-line {
            width: 60px;
            height: 2px;
            background: #dde3f0;
            margin: 0 6px;
        }

        /* Page title */
        .co-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 28px;
        }

        /* Two-column */
        .co-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }
        @media (max-width: 820px) {
            .co-layout { grid-template-columns: 1fr; }
            .co-summary { order: -1; }
        }

        /* ---- LEFT: FORM CARD ---- */
        .co-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 28px 28px;
        }
        .co-section-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .co-field { margin-bottom: 18px; }
        .co-field label {
            display: block;
            font-size: .82rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
            letter-spacing: .3px;
            text-transform: uppercase;
        }
        .co-field textarea {
            width: 100%;
            padding: 13px 16px;
            border: 2px solid #dde3f0;
            border-radius: 10px;
            font-size: .95rem;
            color: #0a0a0a;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            resize: vertical;
            transition: border-color .2s, box-shadow .2s;
        }
        .co-field textarea:focus {
            border-color: #1565c0;
            background: white;
            box-shadow: 0 0 0 4px rgba(21,101,192,.08);
        }

        /* Payment method cards */
        .co-pay-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 4px;
        }
        .co-pay-label {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 2px solid #dde3f0;
            border-radius: 10px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            font-weight: 600;
            color: #333;
            font-size: .92rem;
        }
        .co-pay-label:has(input:checked) {
            border-color: #1565c0;
            background: #e8f0fe;
            color: #0d47a1;
        }
        .co-pay-label input[type="radio"] {
            accent-color: #1565c0;
            width: 16px;
            height: 16px;
        }
        .co-pay-icon { font-size: 1.4rem; }

        /* Submit button */
        .co-submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 16px rgba(21,101,192,.35);
            margin-top: 24px;
            letter-spacing: .3px;
        }
        .co-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(21,101,192,.45);
        }
        .co-submit-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        /* ---- RIGHT: ORDER SUMMARY ---- */
        .co-summary {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 24px 22px;
            position: sticky;
            top: 88px;
        }
        .co-summary h3 {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f2f5;
        }
        .co-sum-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            font-size: .9rem;
            color: #444;
            margin-bottom: 11px;
        }
        .co-sum-item-name {
            flex: 1;
            line-height: 1.4;
        }
        .co-sum-item-qty {
            font-size: .78rem;
            color: #999;
            display: block;
        }
        .co-sum-item-price { font-weight: 700; color: #0a0a0a; white-space: nowrap; }
        .co-sum-divider { height: 1px; background: #f0f2f5; margin: 14px 0; }
        .co-sum-row {
            display: flex;
            justify-content: space-between;
            font-size: .9rem;
            color: #555;
            margin-bottom: 10px;
        }
        .co-sum-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.15rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-top: 4px;
        }
        .co-sum-total span:last-child { color: #1565c0; }

        /* Security note */
        .co-secure {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .78rem;
            color: #999;
            margin-top: 18px;
            justify-content: center;
        }

        /* Empty state */
        .co-empty {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .co-empty-icon { font-size: 3.5rem; margin-bottom: 16px; }
        .co-empty h3 { font-size: 1.2rem; font-weight: 800; color: #0a0a0a; margin-bottom: 8px; }
        .co-empty p  { color: #777; font-size: .92rem; margin-bottom: 26px; }
        .co-empty-btn {
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
        .co-empty-btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="co-page">

    <?php if (!empty($cartItems)): ?>

    <!-- Progress bar -->
    <div class="co-steps">
        <div class="co-step done">
            <div class="co-step-num">✓</div>
            <span>Cart</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step active">
            <div class="co-step-num">2</div>
            <span>Checkout</span>
        </div>
        <div class="co-step-line"></div>
        <div class="co-step">
            <div class="co-step-num">3</div>
            <span>Confirmation</span>
        </div>
    </div>

    <div class="co-title">Checkout</div>

    <div class="co-layout">

        <!-- ===== LEFT: FORM ===== -->
        <div class="co-form-card">
            <form id="checkoutForm">

                <!-- Delivery -->
                <div class="co-section-title">📍 Delivery Details</div>
                <div class="co-field">
                    <label>Delivery Address</label>
                    <textarea name="delivery_address" rows="3" required
                              placeholder="House no., Road, Area, City…"><?= htmlspecialchars($deliveryAddress ?? '') ?></textarea>
                </div>

                <!-- Payment -->
                <div class="co-section-title" style="margin-top:24px;">💳 Payment Method</div>
                <div class="co-field">
                    <div class="co-pay-options">
                        <label class="co-pay-label">
                            <input type="radio" name="payment_method" value="Cash" checked>
                            <span class="co-pay-icon">💵</span>
                            Cash on Delivery
                        </label>
                        <label class="co-pay-label">
                            <input type="radio" name="payment_method" value="Card">
                            <span class="co-pay-icon">💳</span>
                            Pay by Card
                        </label>
                    </div>
                </div>

                <button type="submit" class="co-submit-btn" id="placeOrderBtn">
                    🎉 Place Order
                </button>

            </form>
        </div>

        <!-- ===== RIGHT: SUMMARY ===== -->
        <div class="co-summary">
            <h3>Order Summary</h3>

            <?php foreach ($cartItems as $item): ?>
            <div class="co-sum-item">
                <div class="co-sum-item-name">
                    <?= htmlspecialchars($item['name']) ?>
                    <span class="co-sum-item-qty">× <?= $item['quantity'] ?></span>
                </div>
                <span class="co-sum-item-price">৳<?= number_format($item['total'], 0) ?></span>
            </div>
            <?php endforeach; ?>

            <div class="co-sum-divider"></div>

            <div class="co-sum-row">
                <span>Subtotal</span>
                <span>৳<?= number_format($grandTotal, 0) ?></span>
            </div>
            <div class="co-sum-row">
                <span>Delivery fee</span>
                <span style="color:#1e7e34;font-weight:700;">FREE</span>
            </div>

            <div class="co-sum-divider"></div>

            <div class="co-sum-total">
                <span>Total</span>
                <span>৳<?= number_format($grandTotal, 0) ?></span>
            </div>

            <div class="co-secure">
                🔒 Secure &amp; encrypted checkout
            </div>
        </div>

    </div>

    <?php else: ?>

    <div class="co-empty">
        <div class="co-empty-icon">🛒</div>
        <h3>Your cart is empty</h3>
        <p>Add some items before checking out.</p>
        <a href="/WTech Project/routes/web.php" class="co-empty-btn">Browse Menu</a>
    </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script src="/WTech Project/public/js/checkout.js"></script>
<script>
/* Disable button while placing order */
(function () {
    const form = document.getElementById('checkoutForm');
    const btn  = document.getElementById('placeOrderBtn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.textContent = 'Placing Order…';
        });
    }
})();
</script>

</body>
</html>