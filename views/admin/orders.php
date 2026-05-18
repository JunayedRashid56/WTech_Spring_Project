<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$statusFilter    = trim($_GET['status'] ?? '');
$dateFilter      = trim($_GET['date'] ?? '');
$allowedStatuses = ['Pending', 'Preparing', 'Out for Delivery', 'Delivered'];

$sql = "
    SELECT orders.id, orders.total_amount, orders.status, orders.created_at,
           orders.delivery_address, users.name AS customer_name
    FROM orders JOIN users ON orders.user_id = users.id WHERE 1=1
";
$params = [];
if (!empty($statusFilter)) { $sql .= " AND orders.status = ?"; $params[] = $statusFilter; }
if (!empty($dateFilter))   { $sql .= " AND DATE(orders.created_at) = ?"; $params[] = $dateFilter; }
$sql .= " ORDER BY orders.created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$adminPageTitle = 'Orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders — BiteRush Admin</title>
</head>
<body>

<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<!-- Filter bar -->
<form method="GET" style="background:white;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.06);padding:18px 22px;margin-bottom:22px;display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
    <div class="adm-field" style="margin:0;width:200px;flex-shrink:0;">
        <label>Status</label>
        <select name="status">
            <option value="">All Statuses</option>
            <?php foreach ($allowedStatuses as $s): ?>
                <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="adm-field" style="margin:0;width:180px;flex-shrink:0;">
        <label>Date</label>
        <input type="date" name="date" value="<?= htmlspecialchars($dateFilter) ?>">
    </div>
    <button type="submit" class="adm-btn adm-btn-primary">Filter</button>
    <?php if ($statusFilter || $dateFilter): ?>
        <a href="orders.php" class="adm-btn adm-btn-outline">Clear</a>
    <?php endif; ?>
    <span style="margin-left:auto;font-size:.85rem;color:#888;align-self:center;"><?= count($orders) ?> order<?= count($orders) != 1 ? 's' : '' ?> found</span>
</form>

<!-- Table -->
<div class="adm-card">
    <div class="adm-card-header">
        <div class="adm-card-title">📦 All Orders</div>
    </div>
    <?php if (empty($orders)): ?>
        <div style="text-align:center;padding:60px 20px;color:#aaa;">
            <div style="font-size:2.5rem;margin-bottom:12px;">📭</div>
            <div style="font-weight:700;">No orders found</div>
        </div>
    <?php else: ?>
    <table class="adm-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order):
            $badgeClass = match($order['status']) {
                'Pending'         => 'adm-badge-pending',
                'Preparing'       => 'adm-badge-preparing',
                'Out for Delivery'=> 'adm-badge-delivery',
                'Delivered'       => 'adm-badge-delivered',
                default           => 'adm-badge-cancelled',
            };
        ?>
        <tr>
            <td><strong>#<?= $order['id'] ?></strong></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#777;font-size:.85rem;"><?= htmlspecialchars($order['delivery_address']) ?></td>
            <td><strong>৳<?= number_format($order['total_amount'], 0) ?></strong></td>
            <td>
                <span id="badge-<?= $order['id'] ?>" class="adm-badge <?= $badgeClass ?>">
                    <?= htmlspecialchars($order['status']) ?>
                </span>
            </td>
            <td style="color:#999;font-size:.82rem;"><?= date('d M y, h:i A', strtotime($order['created_at'])) ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <a href="order_detail.php?id=<?= $order['id'] ?>" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                    <select onchange="updateStatus(<?= $order['id'] ?>, this.value); this.value='';"
                            style="padding:5px 8px;border:2px solid #dde3f0;border-radius:8px;font-size:.78rem;font-weight:700;cursor:pointer;outline:none;background:white;color:#333;">
                        <option value="">Update Status</option>
                        <?php foreach ($allowedStatuses as $s): ?>
                            <?php if ($s !== $order['status']): ?>
                                <option value="<?= $s ?>"><?= $s ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>
<script src="/WTech Project/public/js/admin_orders.js"></script>
</body>
</html>