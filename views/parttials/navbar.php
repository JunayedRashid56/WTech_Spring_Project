<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}
?>

<header class="nb-header">
    <div class="nb-inner">

        <!-- LEFT: Logo -->
        <a class="nb-logo" href="/WTech Project/public/index.php">
            <span class="nb-logo-icon">🍽️</span>
            <span class="nb-logo-text">Bite<strong>Rush</strong></span>
        </a>

        <!-- CENTER: Nav links -->
        <nav class="nb-nav">
            <a href="/WTech Project/public/index.php" class="nb-link">Home</a>
            <a href="/WTech Project/routes/web.php" class="nb-link">Menu</a>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin'): ?>
                <a href="/WTech Project/routes/my_orders.php" class="nb-link">My Orders</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/WTech Project/views/admin/dashboard.php" class="nb-link nb-admin-link">⚙ Admin</a>
            <?php endif; ?>
        </nav>

        <!-- RIGHT: Actions -->
        <div class="nb-actions">
            <?php if (isset($_SESSION['user_id'])): ?>

                <?php if ($_SESSION['role'] !== 'admin'): ?>
                <a href="/WTech Project/routes/cart.php" class="nb-cart-btn">
                    <span class="nb-cart-icon">🛒</span>
                    <span class="nb-cart-label">Cart</span>
                    <?php if ($cartCount > 0): ?>
                        <span class="nb-cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <div class="nb-user-menu">
                    <button class="nb-user-btn" onclick="this.parentElement.classList.toggle('open')">
                        <span class="nb-avatar"><?= strtoupper(mb_substr($_SESSION['name'], 0, 1)) ?></span>
                        <span class="nb-user-name"><?= htmlspecialchars(explode(' ', $_SESSION['name'])[0]) ?></span>
                        <span class="nb-chevron">▾</span>
                    </button>
                    <div class="nb-dropdown">
                        <a href="/WTech Project/views/profile/profile.php">👤 My Profile</a>
                        <a href="/WTech Project/routes/my_orders.php">📦 My Orders</a>
                        <div class="nb-dropdown-divider"></div>
                        <a href="/WTech Project/logout.php" class="nb-logout">🚪 Logout</a>
                    </div>
                </div>

            <?php else: ?>
                <a href="/WTech Project/views/auth/login.php" class="nb-login-btn">Login</a>
                <a href="/WTech Project/views/auth/register.php" class="nb-register-btn">Register</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<!-- Spacer so content doesn't hide behind fixed header -->
<div class="nb-spacer"></div>

<script>
/* Close dropdown when clicking outside */
document.addEventListener('click', function(e) {
    document.querySelectorAll('.nb-user-menu.open').forEach(function(m) {
        if (!m.contains(e.target)) m.classList.remove('open');
    });
});
</script>