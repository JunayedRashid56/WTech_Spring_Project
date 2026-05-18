<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; display: flex; min-height: 100vh; }

    /* ---- SIDEBAR ---- */
    .adm-sidebar {
        width: 240px;
        background: linear-gradient(180deg, #0a0a0a 0%, #0d1b2a 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        position: fixed;
        top: 0; left: 0;
        z-index: 100;
    }
    .adm-logo {
        padding: 28px 24px 22px;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .adm-logo-text {
        font-size: 1.3rem;
        font-weight: 900;
        color: white;
        letter-spacing: -.3px;
    }
    .adm-logo-text strong { color: #90caf9; }
    .adm-logo-sub {
        font-size: .72rem;
        color: #90caf9;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .adm-nav { padding: 16px 12px; flex: 1; }
    .adm-nav-label {
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: rgba(255,255,255,.3);
        padding: 10px 12px 6px;
    }
    .adm-nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 14px;
        border-radius: 10px;
        color: rgba(255,255,255,.65);
        text-decoration: none;
        font-size: .9rem;
        font-weight: 600;
        margin-bottom: 3px;
        transition: background .15s, color .15s;
    }
    .adm-nav-link:hover { background: rgba(255,255,255,.08); color: white; }
    .adm-nav-link.active { background: #1565c0; color: white; box-shadow: 0 4px 12px rgba(21,101,192,.4); }
    .adm-nav-icon { font-size: 1rem; width: 20px; text-align: center; }

    .adm-sidebar-footer {
        padding: 16px 12px;
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .adm-sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        margin-bottom: 6px;
    }
    .adm-sidebar-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1565c0, #90caf9);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; font-weight: 900; color: white; flex-shrink: 0;
    }
    .adm-sidebar-name { font-size: .85rem; font-weight: 700; color: white; }
    .adm-sidebar-role { font-size: .72rem; color: #90caf9; }

    /* ---- MAIN CONTENT ---- */
    .adm-main {
        margin-left: 240px;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .adm-topbar {
        background: white;
        border-bottom: 1px solid #e8ecf5;
        padding: 0 32px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .adm-topbar-title {
        font-size: 1.15rem;
        font-weight: 900;
        color: #0a0a0a;
    }
    .adm-topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .adm-view-site {
        font-size: .82rem;
        font-weight: 700;
        color: #1565c0;
        text-decoration: none;
        padding: 7px 14px;
        border: 2px solid #dde3f0;
        border-radius: 20px;
        transition: background .15s;
    }
    .adm-view-site:hover { background: #e8f0fe; }
    .adm-logout-btn {
        font-size: .82rem;
        font-weight: 700;
        color: #c62828;
        text-decoration: none;
        padding: 7px 14px;
        border: 2px solid #fce4ec;
        border-radius: 20px;
        background: white;
        transition: background .15s;
    }
    .adm-logout-btn:hover { background: #fce4ec; }
    .adm-content { padding: 32px; flex: 1; }

    /* ---- SHARED COMPONENTS ---- */
    .adm-page-title {
        font-size: 1.5rem;
        font-weight: 900;
        color: #0a0a0a;
        margin-bottom: 24px;
    }
    .adm-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .adm-card-header {
        padding: 18px 22px;
        border-bottom: 2px solid #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .adm-card-title {
        font-size: .95rem;
        font-weight: 800;
        color: #0a0a0a;
    }
    .adm-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: transform .15s, box-shadow .15s;
    }
    .adm-btn-primary {
        background: linear-gradient(135deg, #0d47a1, #1565c0);
        color: white;
        box-shadow: 0 3px 10px rgba(21,101,192,.25);
    }
    .adm-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(21,101,192,.35); }
    .adm-btn-outline { background: white; color: #555; border: 2px solid #dde3f0; }
    .adm-btn-outline:hover { border-color: #1565c0; color: #1565c0; }
    .adm-btn-danger { background: #fce4ec; color: #c62828; }
    .adm-btn-danger:hover { background: #f8bbd9; }
    .adm-btn-sm { padding: 5px 12px; font-size: .78rem; }

    /* Table */
    .adm-table { width: 100%; border-collapse: collapse; }
    .adm-table th {
        background: #f8f9fc;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #888;
        padding: 12px 18px;
        text-align: left;
        border-bottom: 2px solid #f0f2f5;
    }
    .adm-table td {
        padding: 14px 18px;
        font-size: .9rem;
        color: #333;
        border-bottom: 1px solid #f5f6fa;
        vertical-align: middle;
    }
    .adm-table tr:last-child td { border-bottom: none; }
    .adm-table tr:hover td { background: #fafbff; }

    /* Status badges */
    .adm-badge {
        display: inline-block;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
    }
    .adm-badge-pending       { background: #fff3e0; color: #e65100; }
    .adm-badge-preparing     { background: #e3f2fd; color: #0d47a1; }
    .adm-badge-delivery      { background: #f3e5f5; color: #6a1b9a; }
    .adm-badge-delivered     { background: #e8f5e9; color: #1b5e20; }
    .adm-badge-cancelled     { background: #fce4ec; color: #880e4f; }
    .adm-badge-active        { background: #e8f5e9; color: #1b5e20; }
    .adm-badge-inactive      { background: #f5f5f5; color: #888; }

    /* Flash */
    .adm-flash {
        padding: 13px 18px;
        border-radius: 10px;
        font-size: .9rem;
        font-weight: 600;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .adm-flash-success { background: #e8f5e9; color: #1b5e20; }
    .adm-flash-error   { background: #fce4ec; color: #880e4f; }

    /* Form fields */
    .adm-field { margin-bottom: 18px; }
    .adm-field label {
        display: block;
        font-size: .78rem;
        font-weight: 700;
        color: #555;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .adm-field input, .adm-field select, .adm-field textarea {
        width: 100%;
        padding: 11px 14px;
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
    .adm-field input:focus, .adm-field select:focus, .adm-field textarea:focus {
        border-color: #1565c0;
        background: white;
        box-shadow: 0 0 0 4px rgba(21,101,192,.08);
    }
</style>

<div class="adm-sidebar">
    <div class="adm-logo">
        <div class="adm-logo-text">Bite<strong>Rush</strong></div>
        <div class="adm-logo-sub">Admin Panel</div>
    </div>

    <nav class="adm-nav">
        <div class="adm-nav-label">Main</div>
        <a href="/WTech Project/views/admin/dashboard.php"
           class="adm-nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <span class="adm-nav-icon">📊</span> Dashboard
        </a>

        <div class="adm-nav-label" style="margin-top:10px;">Manage</div>
        <a href="/WTech Project/views/admin/orders.php"
           class="adm-nav-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>">
            <span class="adm-nav-icon">📦</span> Orders
        </a>
        <a href="/WTech Project/views/admin/menu_items.php"
           class="adm-nav-link <?= $currentPage === 'menu_items.php' ? 'active' : '' ?>">
            <span class="adm-nav-icon">🍽️</span> Menu Items
        </a>
        <a href="/WTech Project/views/admin/categories.php"
           class="adm-nav-link <?= $currentPage === 'categories.php' ? 'active' : '' ?>">
            <span class="adm-nav-icon">🗂️</span> Categories
        </a>
    </nav>

    <div class="adm-sidebar-footer">
        <div class="adm-sidebar-user">
            <div class="adm-sidebar-avatar"><?= strtoupper(mb_substr($_SESSION['name'] ?? 'A', 0, 1)) ?></div>
            <div>
                <div class="adm-sidebar-name"><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></div>
                <div class="adm-sidebar-role">Administrator</div>
            </div>
        </div>
        <a href="/WTech Project/logout.php" class="adm-nav-link" style="color:#f48fb1;">
            <span class="adm-nav-icon">🚪</span> Logout
        </a>
    </div>
</div>

<div class="adm-main">
    <div class="adm-topbar">
        <div class="adm-topbar-title"><?= $adminPageTitle ?? 'Admin' ?></div>
        <div class="adm-topbar-right"></div>
    </div>
    <div class="adm-content">
