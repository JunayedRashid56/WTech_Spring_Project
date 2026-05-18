<?php
session_start();

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../../config/database.php';
    require_once __DIR__ . '/../../models/User.php';

    $userModel = new User($pdo);
    $user = $userModel->findByRememberToken($_COOKIE['remember_token']);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['email']   = $user['email'];

        if ($user['role'] === 'admin') {
            header("Location: /WTech Project/views/admin/dashboard.php");
        } else {
            header("Location: /WTech Project/routes/web.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        /* ---- Auth page layout ---- */
        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* LEFT: hero panel */
        .auth-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0a0a0a 0%, #0d1b2a 50%, #1565c0 100%);
        }
        .auth-hero-bg {
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900&q=80') center/cover no-repeat;
            opacity: .22;
        }
        .auth-hero-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 44px;
        }
        .auth-hero-logo {
            font-size: 1.7rem;
            font-weight: 900;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .auth-hero-logo span { color: rgba(255,255,255,.7); font-weight: 400; }
        .auth-hero-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .auth-hero-tag {
            display: inline-block;
            background: rgba(255,255,255,.15);
            color: #e3f2fd;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 22px;
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,.2);
        }
        .auth-hero-body h2 {
            font-size: 2.6rem;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.18;
            margin-bottom: 18px;
            text-shadow: 0 2px 12px rgba(0,0,0,.35);
        }
        .auth-hero-body h2 span { color: #90caf9; }
        .auth-hero-body p {
            color: #bbdefb;
            font-size: 1rem;
            line-height: 1.7;
            max-width: 380px;
            margin-bottom: 36px;
        }
        .auth-hero-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .auth-hero-feat {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e3f2fd;
            font-size: .92rem;
            font-weight: 500;
        }
        .auth-hero-feat-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,.18);
        }
        .auth-hero-bottom {
            color: rgba(255,255,255,.45);
            font-size: .8rem;
        }

        /* RIGHT: form panel */
        .auth-form-panel {
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }
        .auth-form-box {
            width: 100%;
            max-width: 420px;
        }
        .auth-form-box .auth-top-brand {
            display: none;
            font-size: 1.4rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 28px;
            text-align: center;
        }
        .auth-form-box h2 {
            font-size: 1.9rem;
            font-weight: 900;
            color: #0a0a0a;
            margin-bottom: 6px;
        }
        .auth-form-box .auth-subtitle {
            color: #555;
            font-size: .95rem;
            margin-bottom: 32px;
        }
        .auth-field {
            margin-bottom: 18px;
        }
        .auth-field label {
            display: block;
            font-size: .83rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
            letter-spacing: .3px;
            text-transform: uppercase;
        }
        .auth-field input {
            width: 100%;
            padding: 13px 16px;
            border: 2px solid #dde3f0;
            border-radius: 10px;
            font-size: .97rem;
            color: #0a0a0a;
            background: white;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .auth-field input:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21,101,192,.1);
        }
        .auth-remember {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 24px;
            font-size: .9rem;
            color: #444;
            font-weight: 500;
        }
        .auth-remember input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: #1565c0;
            cursor: pointer;
        }
        .auth-submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            font-size: 1rem;
            font-weight: 800;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            letter-spacing: .3px;
            box-shadow: 0 4px 16px rgba(21,101,192,.35);
        }
        .auth-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(21,101,192,.45);
        }
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #aaa;
            font-size: .83rem;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #dde3f0;
        }
        .auth-bottom-link {
            text-align: center;
            font-size: .92rem;
            color: #555;
            margin-top: 22px;
        }
        .auth-bottom-link a {
            color: #1565c0;
            font-weight: 700;
        }
        .auth-bottom-link a:hover { text-decoration: underline; }

        /* Flash messages */
        .auth-flash-success {
            background: #e6f9ee;
            color: #1e7e34;
            border: 1px solid #b8e8c8;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: .9rem;
            margin-bottom: 18px;
        }
        .auth-flash-error {
            background: #fdecea;
            color: #c62828;
            border: 1px solid #f5c6c6;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: .9rem;
            margin-bottom: 18px;
        }

        /* Account for fixed navbar height */
        .auth-page { min-height: calc(100vh - 71px); }

        /* Responsive: stack on mobile */
        @media (max-width: 820px) {
            .auth-page { grid-template-columns: 1fr; }
            .auth-hero  { display: none; }
            .auth-form-box .auth-top-brand { display: block; }
            .auth-form-panel { min-height: calc(100vh - 71px); }
        }
    </style>
</head>
<body style="margin:0; padding:0; padding-top:0;">

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="auth-page">

    <!-- ===== LEFT HERO PANEL ===== -->
    <div class="auth-hero">
        <div class="auth-hero-bg"></div>
        <div class="auth-hero-content">
            <a class="auth-hero-logo" href="/WTech Project/public/index.php">
                🍽️ <span>Bite</span>Rush
            </a>
            <div class="auth-hero-body">
                <span class="auth-hero-tag">🔥 Bangladesh's Favourite Food App</span>
                <h2>Good food is<br>just a click <span>away.</span></h2>
                <p>Sign in to track your orders, reorder your favourites, and unlock exclusive deals delivered straight to your door.</p>
                <div class="auth-hero-features">
                    <div class="auth-hero-feat">
                        <div class="auth-hero-feat-icon">⚡</div>
                        <span>30-minute delivery guarantee</span>
                    </div>
                    <div class="auth-hero-feat">
                        <div class="auth-hero-feat-icon">📍</div>
                        <span>Live order tracking in real time</span>
                    </div>
                    <div class="auth-hero-feat">
                        <div class="auth-hero-feat-icon">🍱</div>
                        <span>Fresh from 2,000+ local restaurants</span>
                    </div>
                </div>
            </div>
            <div class="auth-hero-bottom">&copy; <?= date('Y') ?> BiteRush · All rights reserved</div>
        </div>
    </div>

    <!-- ===== RIGHT FORM PANEL ===== -->
    <div class="auth-form-panel">
        <div class="auth-form-box">

            <div class="auth-top-brand">🍽️ BiteRush</div>

            <h2>Welcome back</h2>
            <p class="auth-subtitle">Sign in to continue your order</p>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="auth-flash-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="auth-flash-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/WTech Project/controllers/AuthController.php?action=login">

                <div class="auth-field">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required autofocus>
                </div>

                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="auth-remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="cursor:pointer;">Remember me for 30 days</label>
                </div>

                <button type="submit" class="auth-submit-btn">Sign In →</button>

            </form>

            <div class="auth-divider">or</div>

            <div class="auth-bottom-link">
                Don't have an account?
                <a href="/WTech Project/views/auth/register.php">Create one free →</a>
            </div>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

</body>
</html>