<?php
require_once __DIR__ . '/../../config/auth_check.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';

$userModel = new User($pdo);
$user      = $userModel->findByEmail($_SESSION['email']);

$initials  = strtoupper(mb_substr($user['name'], 0, 1));
$firstName = explode(' ', $user['name'])[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        .prf-page {
            max-width: 860px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }

        /* ---- HERO BANNER ---- */
        .prf-banner {
            background: linear-gradient(135deg, #0a0a0a 0%, #0d1b2a 55%, #1565c0 100%);
            border-radius: 18px;
            padding: 36px 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }
        .prf-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1000&q=40') center/cover;
            opacity: .05;
        }
        .prf-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1565c0, #90caf9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 900;
            color: white;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 16px rgba(0,0,0,.3);
        }
        .prf-banner-info { position: relative; z-index: 1; }
        .prf-banner-name {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 4px;
        }
        .prf-banner-email {
            font-size: .88rem;
            color: #90caf9;
            margin-bottom: 8px;
        }
        .prf-banner-role {
            display: inline-block;
            background: rgba(255,255,255,.15);
            color: white;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        /* Flash messages */
        .prf-flash {
            padding: 13px 18px;
            border-radius: 10px;
            font-size: .9rem;
            font-weight: 600;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .prf-flash.success { background: #e8f5e9; color: #1b5e20; }
        .prf-flash.error   { background: #fce4ec; color: #880e4f; }

        /* Two-column layout */
        .prf-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            align-items: start;
        }
        @media (max-width: 700px) {
            .prf-layout { grid-template-columns: 1fr; }
        }

        /* Cards */
        .prf-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 26px 26px;
        }
        .prf-card-title {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid #f0f2f5;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form fields */
        .prf-field { margin-bottom: 16px; }
        .prf-field label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: #555;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .prf-field input,
        .prf-field textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #dde3f0;
            border-radius: 10px;
            font-size: .92rem;
            color: #0a0a0a;
            background: #fafafa;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box;
        }
        .prf-field input:focus,
        .prf-field textarea:focus {
            border-color: #1565c0;
            background: white;
            box-shadow: 0 0 0 4px rgba(21,101,192,.08);
        }
        .prf-field textarea { resize: vertical; min-height: 80px; }

        /* Submit buttons */
        .prf-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            font-size: .95rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 4px 14px rgba(21,101,192,.3);
            margin-top: 6px;
        }
        .prf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(21,101,192,.4);
        }

        /* Quick links card (bottom) */
        .prf-links {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            padding: 20px 26px;
            margin-top: 22px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .prf-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: .88rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s;
        }
        .prf-link-primary {
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            box-shadow: 0 3px 10px rgba(21,101,192,.25);
        }
        .prf-link-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(21,101,192,.35); }
        .prf-link-outline {
            border: 2px solid #dde3f0;
            color: #555;
            background: white;
        }
        .prf-link-outline:hover { border-color: #1565c0; color: #1565c0; }
        .prf-link-danger {
            border: 2px solid #fce4ec;
            color: #c62828;
            background: white;
        }
        .prf-link-danger:hover { background: #fce4ec; }
    </style>
</head>
<body>

<?php include '../partials/navbar.php'; ?>

<div class="prf-page">

    <!-- Banner -->
    <div class="prf-banner">
        <div class="prf-avatar"><?= $initials ?></div>
        <div class="prf-banner-info">
            <div class="prf-banner-name"><?= htmlspecialchars($user['name']) ?></div>
            <div class="prf-banner-email"><?= htmlspecialchars($user['email']) ?></div>
            <span class="prf-banner-role">👤 Customer</span>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="prf-flash success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="prf-flash error">❌ <?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Two-column forms -->
    <div class="prf-layout">

        <!-- Update Profile -->
        <div class="prf-card">
            <div class="prf-card-title">✏️ Edit Profile</div>
            <form method="POST" action="../../controllers/ProfileController.php?action=updateProfile">
                <div class="prf-field">
                    <label>Full Name</label>
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="prf-field">
                    <label>Email Address</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="prf-field">
                    <label>Delivery Address</label>
                    <textarea name="address" placeholder="House no., Road, Area, City…"><?= htmlspecialchars($user['delivery_address'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="prf-btn">Save Changes</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="prf-card">
            <div class="prf-card-title">🔒 Change Password</div>
            <form method="POST" action="../../controllers/ProfileController.php?action=changePassword">
                <div class="prf-field">
                    <label>Current Password</label>
                    <input type="password" name="current_password"
                           placeholder="Enter current password" required>
                </div>
                <div class="prf-field">
                    <label>New Password</label>
                    <input type="password" name="new_password"
                           placeholder="Enter new password" required>
                </div>
                <div class="prf-field">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password"
                           placeholder="Re-enter new password">
                </div>
                <button type="submit" class="prf-btn">Update Password</button>
            </form>
        </div>

    </div>

    <!-- Quick links -->
    <div class="prf-links">
        <a href="/WTech Project/routes/my_orders.php" class="prf-link prf-link-primary">📦 My Orders</a>
        <a href="/WTech Project/routes/web.php" class="prf-link prf-link-outline">🍽️ Browse Menu</a>
        <a href="/WTech Project/routes/cart.php" class="prf-link prf-link-outline">🛒 My Cart</a>
        <a href="/WTech Project/logout.php" class="prf-link prf-link-danger">🚪 Logout</a>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>