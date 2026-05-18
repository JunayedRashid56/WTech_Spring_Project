<?php
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Order.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$orderId    = (int) $_GET['id'];
$orderModel = new Order($pdo);

$sql = "
    SELECT orders.*, users.name AS customer_name, users.email AS customer_email
    FROM orders
    JOIN users ON orders.user_id = users.id
    WHERE orders.id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: orders.php");
    exit();
}

$items = $orderModel->getOrderItems($orderId);

$allowedTransitions = [
    'Pending'         => 'Preparing',
    'Preparing'       => 'Out for Delivery',
    'Out for Delivery'=> 'Delivered',
];
$nextStatus = $allowedTransitions[$order['status']] ?? null;

/* Progress stepper */
$steps       = ['Pending', 'Preparing', 'Out for Delivery', 'Delivered'];
$stepIcons   = ['🕐', '👨‍🍳', '🛵', '✅'];
$currentStep = array_search($order['status'], $steps);
if ($currentStep === false) $currentStep = 0;

$badgeClass = match($order['status']) {
    'Pending'         => 'adm-badge-pending',
    'Preparing'       => 'adm-badge-preparing',
    'Out for Delivery'=> 'adm-badge-delivery',
    'Delivered'       => 'adm-badge-delivered',
    default           => 'adm-badge-cancelled',
};

$adminPageTitle = 'Order #' . $orderId;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?= $orderId ?> — BiteRush Admin</title>
    <style>
        .od-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 22px;
            align-items: start;
        }
        @media (max-width: 900px) { .od-layout { grid-template-columns: 1fr; } }

        /* Info grid inside left card */
        .od-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }
        .od-info-row {
            padding: 13px 0;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .od-info-row:nth-child(odd)  { padding-right: 24px; border-right: 1px solid #f0f2f5; }
        .od-info-row:nth-child(even) { padding-left: 24px; }
        .od-info-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #aaa; }
        .od-info-value { font-size: .92rem; font-weight: 600; color: #0a0a0a; }

        /* Stepper */
        .od-stepper {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 24px 0 8px;
        }
        .od-stepper::before {
            content: '';
            position: absolute;
            top: 18px; left: 18px; right: 18px;
            height: 3px;
            background: #dde3f0;
            z-index: 0;
        }
        .od-step {
            display: flex; flex-direction: column;
            align-items: center; gap: 7px;
            flex: 1; position: relative; z-index: 1;
        }
        .od-step-circle {
            width: 38px; height: 38px; border-radius: 50%;
            background: #f0f2f5; border: 3px solid #dde3f0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .od-step.done .od-step-circle   { background: #1565c0; border-color: #1565c0; }
        .od-step.active .od-step-circle { background: white; border-color: #1565c0; box-shadow: 0 0 0 4px rgba(21,101,192,.15); }
        .od-step-label { font-size: .68rem; font-weight: 700; color: #bbb; text-align: center; line-height: 1.3; }
        .od-step.done .od-step-label   { color: #1565c0; }
        .od-step.active .od-step-label { color: #0a0a0a; }

        /* Right summary panel */
        .od-summary {
            position: sticky;
            top: 88px;
        }
        .od-summary-inner {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            overflow: hidden;
            margin-bottom: 16px;
        }
        .od-summary-header {
            padding: 16px 20px;
            border-bottom: 2px solid #f0f2f5;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #aaa;
        }
        .od-summary-body { padding: 16px 20px; }
        .od-sum-row {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
            color: #555;
            margin-bottom: 10px;
        }
        .od-sum-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 900;
            color: #0a0a0a;
            padding-top: 12px;
            border-top: 2px solid #f0f2f5;
            margin-top: 4px;
        }
        .od-sum-total span:last-child { color: #1565c0; }

        /* Advance button */
        .od-advance-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-size: .95rem;
            font-weight: 800;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 14px rgba(21,101,192,.3);
        }
        .od-advance-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(21,101,192,.4); }
        .od-advance-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
        .od-delivered-note {
            text-align: center;
            padding: 13px;
            background: #e8f5e9;
            border-radius: 10px;
            color: #1b5e20;
            font-size: .88rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<!-- Back link -->
<a href="orders.php" style="display:inline-flex;align-items:center;gap:6px;font-size:.88rem;font-weight:600;color:#1565c0;text-decoration:none;margin-bottom:22px;">
    ← Back to Orders
</a>

<!-- Title row -->
<div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;flex-wrap:wrap;">
    <div style="font-size:1.5rem;font-weight:900;color:#0a0a0a;">Order #<?= $orderId ?></div>
    <span id="statusBadge" class="adm-badge <?= $badgeClass ?>"><?= htmlspecialchars($order['status']) ?></span>
    <span style="font-size:.82rem;color:#aaa;margin-left:auto;"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
</div>

<div class="od-layout">

    <!-- ===== LEFT ===== -->
    <div>

        <!-- Order info -->
        <div class="adm-card" style="margin-bottom:20px;">
            <div class="adm-card-header"><div class="adm-card-title">📋 Order Details</div></div>
            <div style="padding:4px 22px 18px;">
                <div class="od-info-grid">
                    <div class="od-info-row">
                        <span class="od-info-label">Customer</span>
                        <span class="od-info-value"><?= htmlspecialchars($order['customer_name']) ?></span>
                    </div>
                    <div class="od-info-row">
                        <span class="od-info-label">Email</span>
                        <span class="od-info-value" style="font-size:.85rem;"><?= htmlspecialchars($order['customer_email'] ?? '—') ?></span>
                    </div>
                    <div class="od-info-row">
                        <span class="od-info-label">Payment Method</span>
                        <span class="od-info-value"><?= htmlspecialchars($order['payment_method']) ?></span>
                    </div>
                    <div class="od-info-row">
                        <span class="od-info-label">Order Date</span>
                        <span class="od-info-value" style="font-size:.85rem;"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="od-info-row" style="grid-column:1/-1;border-right:none;padding-right:0;">
                        <span class="od-info-label">Delivery Address</span>
                        <span class="od-info-value"><?= htmlspecialchars($order['delivery_address']) ?></span>
                    </div>
                </div>

                <!-- Progress stepper -->
                <div style="margin-top:20px;">
                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#aaa;margin-bottom:6px;">Order Progress</div>
                    <div class="od-stepper">
                        <?php foreach ($steps as $i => $step): ?>
                        <div class="od-step <?= $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') ?>" id="adm-step-<?= $i ?>">
                            <div class="od-step-circle"><?= $stepIcons[$i] ?></div>
                            <div class="od-step-label"><?= $step ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items table -->
        <div class="adm-card">
            <div class="adm-card-header"><div class="adm-card-title">🍽️ Items Ordered</div></div>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                    <td><?= (int) $item['quantity'] ?></td>
                    <td>৳<?= number_format($item['unit_price'], 0) ?></td>
                    <td><strong>৳<?= number_format($item['unit_price'] * $item['quantity'], 0) ?></strong></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- ===== RIGHT ===== -->
    <div class="od-summary">

        <!-- Amount summary -->
        <div class="od-summary-inner">
            <div class="od-summary-header">Payment Summary</div>
            <div class="od-summary-body">
                <?php
                $subtotal = 0;
                foreach ($items as $item) $subtotal += $item['unit_price'] * $item['quantity'];
                ?>
                <div class="od-sum-row"><span>Subtotal</span><span>৳<?= number_format($subtotal, 0) ?></span></div>
                <div class="od-sum-row"><span>Delivery fee</span><span style="color:#1e7e34;font-weight:700;">FREE</span></div>
                <div class="od-sum-total">
                    <span>Total</span>
                    <span>৳<?= number_format($order['total_amount'], 0) ?></span>
                </div>
            </div>
        </div>

        <!-- Advance status -->
        <div class="od-summary-inner">
            <div class="od-summary-header">Update Status</div>
            <div class="od-summary-body">
                <?php if ($nextStatus): ?>
                    <button class="od-advance-btn" id="advanceBtn"
                            onclick="advanceStatus(<?= $orderId ?>, '<?= $nextStatus ?>')">
                        → Mark as <?= htmlspecialchars($nextStatus) ?>
                    </button>
                    <div style="font-size:.75rem;color:#aaa;text-align:center;margin-top:10px;">
                        Current: <strong><?= htmlspecialchars($order['status']) ?></strong>
                    </div>
                <?php else: ?>
                    <div class="od-delivered-note">✅ Order is <?= htmlspecialchars($order['status']) ?></div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>

<script>
const statusStepMap = { 'Pending':0, 'Preparing':1, 'Out for Delivery':2, 'Delivered':3 };
const badgeClassMap = {
    'Pending':'adm-badge-pending', 'Preparing':'adm-badge-preparing',
    'Out for Delivery':'adm-badge-delivery', 'Delivered':'adm-badge-delivered'
};

function advanceStatus(orderId, status) {
    const btn = document.getElementById('advanceBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Updating…'; }

    fetch('/WTech Project/api/orders/update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId, status: status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            /* Update badge */
            const badge = document.getElementById('statusBadge');
            badge.textContent = data.status;
            badge.className = 'adm-badge ' + (badgeClassMap[data.status] || 'adm-badge-cancelled');

            /* Update stepper */
            const idx = statusStepMap[data.status] ?? 0;
            document.querySelectorAll('.od-step').forEach((el, i) => {
                el.classList.remove('done','active');
                if (i < idx) el.classList.add('done');
                else if (i === idx) el.classList.add('active');
            });

            /* Remove advance button */
            if (btn) btn.closest('.od-summary-inner').querySelector('.od-summary-body').innerHTML =
                '<div class="od-delivered-note">✅ Order is ' + data.status + '</div>';
        } else {
            alert(data.message || 'Update failed');
            if (btn) { btn.disabled = false; btn.textContent = '→ Mark as ' + status; }
        }
    });
}
</script>

</body>
</html>
