<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /WTech Project/views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Order.php';

if (!isset($_GET['id'])) {
    header('Location: /WTech Project/routes/my_orders.php');
    exit;
}

$orderId    = (int) $_GET['id'];
$userId     = $_SESSION['user_id'];
$orderModel = new Order();
$order      = $orderModel->getOrderStatus($orderId, $userId);

if (!$order) {
    header('Location: /WTech Project/routes/my_orders.php');
    exit;
}

$orderItems = $orderModel->getOrderItems($orderId);

/* Status → step index (0-based) */
$steps = ['Pending', 'Preparing', 'Out for Delivery', 'Delivered'];
$currentStep = array_search($order['status'], $steps);
if ($currentStep === false) $currentStep = 0;

$stepIcons = ['🕐', '👨‍🍳', '🛵', '✅'];
$stepLabels = ['Order Placed', 'Preparing', 'Out for Delivery', 'Delivered'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #<?= $orderId ?> — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .trk-page {
            max-width: 660px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }

        /* Back link */
        .trk-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .88rem;
            font-weight: 600;
            color: #1565c0;
            text-decoration: none;
            margin-bottom: 28px;
        }
        .trk-back:hover { text-decoration: underline; }

        /* Title row */
        .trk-title {
            font-size: 1.7rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 6px;
        }
        .trk-date {
            font-size: .88rem;
            color: #999;
            margin-bottom: 32px;
        }

        /* Status badge */
        .trk-badge {
            display: inline-block;
            padding: 7px 18px;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            margin-bottom: 32px;
        }
        .Pending          { background: #fff3e0; color: #e65100; }
        .Preparing        { background: #e3f2fd; color: #0d47a1; }
        .Out-for-Delivery { background: #f3e5f5; color: #6a1b9a; }
        .Delivered        { background: #e8f5e9; color: #1b5e20; }
        .Cancelled        { background: #fce4ec; color: #880e4f; }

        /* Progress stepper */
        .trk-stepper {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 28px 24px;
            margin-bottom: 22px;
        }
        .trk-stepper-title {
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #aaa;
            margin-bottom: 24px;
        }
        .trk-steps {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
        }
        /* Connecting line behind steps */
        .trk-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            height: 3px;
            background: #dde3f0;
            z-index: 0;
        }
        .trk-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
            z-index: 1;
        }
        .trk-step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f0f2f5;
            border: 3px solid #dde3f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background .3s, border-color .3s;
        }
        .trk-step.done .trk-step-circle {
            background: #1565c0;
            border-color: #1565c0;
        }
        .trk-step.active .trk-step-circle {
            background: white;
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21,101,192,.15);
        }
        .trk-step-label {
            font-size: .72rem;
            font-weight: 700;
            color: #bbb;
            text-align: center;
            line-height: 1.3;
        }
        .trk-step.done .trk-step-label   { color: #1565c0; }
        .trk-step.active .trk-step-label  { color: #0a0a0a; }

        /* Pulse on active step */
        @keyframes trk-pulse {
            0%,100% { box-shadow: 0 0 0 4px rgba(21,101,192,.15); }
            50%      { box-shadow: 0 0 0 8px rgba(21,101,192,.08); }
        }
        .trk-step.active .trk-step-circle { animation: trk-pulse 2s ease-in-out infinite; }

        /* ETA strip */
        .trk-eta {
            background: linear-gradient(135deg, #0a0a0a, #0d1b2a);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            color: white;
        }
        .trk-eta-label { font-size: .82rem; color: #90caf9; font-weight: 600; }
        .trk-eta-time  { font-size: 1.3rem; font-weight: 900; }
        .trk-eta-icon  { font-size: 2rem; }

        /* Items card */
        .trk-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 22px 24px;
            margin-bottom: 22px;
        }
        .trk-card-title {
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #aaa;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f2f5;
        }
        .trk-item-row {
            display: flex;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: .9rem;
            color: #333;
        }
        .trk-item-row:last-child { border-bottom: none; }
        .trk-item-name { font-weight: 600; }
        .trk-item-qty  { color: #999; font-size: .82rem; }
        .trk-item-price { font-weight: 700; color: #0a0a0a; }

        .trk-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 2px solid #f0f2f5;
        }
        .trk-total-row span:last-child { color: #1565c0; }

        /* Live update note */
        .trk-live-note {
            text-align: center;
            font-size: .8rem;
            color: #bbb;
            margin-top: 12px;
        }
        .trk-live-dot {
            display: inline-block;
            width: 7px; height: 7px;
            background: #43a047;
            border-radius: 50%;
            margin-right: 5px;
            animation: blink 1.4s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="trk-page">

    <a href="/WTech Project/routes/my_orders.php" class="trk-back">← Back to My Orders</a>

    <div class="trk-title">Order #<?= $orderId ?></div>
    <div class="trk-date">
        Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
    </div>

    <div id="statusBadge" class="trk-badge <?= str_replace(' ', '-', $order['status']) ?>">
        <?= htmlspecialchars($order['status']) ?>
    </div>

    <!-- Progress stepper -->
    <div class="trk-stepper">
        <div class="trk-stepper-title">Order Progress</div>
        <div class="trk-steps">
            <?php foreach ($stepLabels as $i => $label): ?>
            <div class="trk-step <?= $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') ?>"
                 id="step-<?= $i ?>">
                <div class="trk-step-circle"><?= $stepIcons[$i] ?></div>
                <div class="trk-step-label"><?= $label ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ETA -->
    <?php if ($order['status'] !== 'Delivered' && $order['status'] !== 'Cancelled'): ?>
    <div class="trk-eta">
        <div>
            <div class="trk-eta-label">Estimated Delivery</div>
            <div class="trk-eta-time">25 – 40 min</div>
        </div>
        <div class="trk-eta-icon">🛵</div>
    </div>
    <?php endif; ?>

    <!-- Items -->
    <?php if (!empty($orderItems)): ?>
    <div class="trk-card">
        <div class="trk-card-title">Items Ordered</div>
        <?php
        $grandTotal = 0;
        foreach ($orderItems as $item):
            $lineTotal = $item['unit_price'] * $item['quantity'];
            $grandTotal += $lineTotal;
        ?>
        <div class="trk-item-row">
            <span>
                <span class="trk-item-name"><?= htmlspecialchars($item['name']) ?></span>
                <span class="trk-item-qty"> × <?= $item['quantity'] ?></span>
            </span>
            <span class="trk-item-price">৳<?= number_format($lineTotal, 0) ?></span>
        </div>
        <?php endforeach; ?>
        <div class="trk-total-row">
            <span>Total</span>
            <span>৳<?= number_format($grandTotal, 0) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Live update note -->
    <div class="trk-live-note">
        <span class="trk-live-dot"></span>
        Status updates automatically every 10 seconds
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script>
    const orderId = <?= $orderId ?>;

    /* Map status string to step index */
    const statusStepMap = {
        'Pending': 0,
        'Preparing': 1,
        'Out for Delivery': 2,
        'Delivered': 3
    };

    function updateStatusBadge(status) {
        const badge = document.getElementById('statusBadge');
        badge.textContent = status;
        badge.className = 'trk-badge ' + status.replace(/ /g, '-');

        /* Update stepper */
        const stepIdx = statusStepMap[status] ?? 0;
        document.querySelectorAll('.trk-step').forEach((el, i) => {
            el.classList.remove('done', 'active');
            if (i < stepIdx)      el.classList.add('done');
            else if (i === stepIdx) el.classList.add('active');
        });
    }
</script>
<script src="/WTech Project/public/js/order_status.js"></script>

</body>
</html>