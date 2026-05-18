<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$totalCategories  = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$totalItems       = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
$unavailableItems = $pdo->query("SELECT COUNT(*) FROM menu_items WHERE is_available = 0")->fetchColumn();
$totalOrders      = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders    = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$totalRevenue     = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status = 'Delivered'")->fetchColumn();

$recentOrders = $pdo->query("
    SELECT orders.id, orders.total_amount, orders.status, orders.created_at,
           users.name AS customer_name
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.created_at DESC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

$adminPageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — BiteRush Admin</title>
</head>
<body>

<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<!-- Stat cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:28px;">

    <?php
    $stats = [
        ['label'=>'Total Orders',      'value'=>$totalOrders,      'icon'=>'📦', 'color'=>'#1565c0', 'bg'=>'#e3f2fd'],
        ['label'=>'Pending Orders',    'value'=>$pendingOrders,    'icon'=>'🕐', 'color'=>'#e65100', 'bg'=>'#fff3e0'],
        ['label'=>'Menu Items',        'value'=>$totalItems,       'icon'=>'🍽️', 'color'=>'#1b5e20', 'bg'=>'#e8f5e9'],
        ['label'=>'Categories',        'value'=>$totalCategories,  'icon'=>'🗂️', 'color'=>'#6a1b9a', 'bg'=>'#f3e5f5'],
        ['label'=>'Unavailable Items', 'value'=>$unavailableItems, 'icon'=>'🚫', 'color'=>'#880e4f', 'bg'=>'#fce4ec'],
        ['label'=>'Revenue (Delivered)','value'=>'৳'.number_format($totalRevenue,0),'icon'=>'💰','color'=>'#1b5e20','bg'=>'#e8f5e9'],
    ];
    foreach ($stats as $s): ?>
    <div style="background:white;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.06);padding:20px 22px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#888;"><?= $s['label'] ?></span>
            <span style="width:36px;height:36px;border-radius:10px;background:<?= $s['bg'] ?>;display:flex;align-items:center;justify-content:center;font-size:1.1rem;"><?= $s['icon'] ?></span>
        </div>
        <div style="font-size:1.8rem;font-weight:900;color:<?= $s['color'] ?>;"><?= $s['value'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Recent Orders -->
<div class="adm-card">
    <div class="adm-card-header">
        <div class="adm-card-title">📋 Recent Orders</div>
        <a href="/WTech Project/views/admin/orders.php" class="adm-btn adm-btn-outline adm-btn-sm">View All</a>
    </div>
    <table class="adm-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($recentOrders as $o):
            $sc = str_replace(' ', '-', $o['status']);
            $badgeClass = match($o['status']) {
                'Pending'         => 'adm-badge-pending',
                'Preparing'       => 'adm-badge-preparing',
                'Out for Delivery'=> 'adm-badge-delivery',
                'Delivered'       => 'adm-badge-delivered',
                default           => 'adm-badge-cancelled',
            };
        ?>
        <tr>
            <td><strong>#<?= $o['id'] ?></strong></td>
            <td><?= htmlspecialchars($o['customer_name']) ?></td>
            <td>৳<?= number_format($o['total_amount'], 0) ?></td>
            <td><span class="adm-badge <?= $badgeClass ?>"><?= htmlspecialchars($o['status']) ?></span></td>
            <td style="color:#999;font-size:.82rem;"><?= date('d M, h:i A', strtotime($o['created_at'])) ?></td>
            <td><a href="/WTech Project/views/admin/order_detail.php?id=<?= $o['id'] ?>" class="adm-btn adm-btn-outline adm-btn-sm">View</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>
</body>
</html>