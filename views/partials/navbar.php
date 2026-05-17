<nav>

    <a href="../../public/index.php">Home</a>

    <?php if (isset($_SESSION['user_id'])): ?>

        <a href="../profile/profile.php">Profile</a>
        <a href="../../logout.php">Logout</a>

    <?php else: ?>

        <a href="../auth/login.php">Login</a>
        <a href="../auth/register.php">Register</a>

    <?php endif; ?>

</nav>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;

if (isset($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}
?>

<a href="../../views/cart/index.php">
    Cart (
    <span id="cart-count">
        <?php echo $cartCount; ?>
    </span>
    )
</a>