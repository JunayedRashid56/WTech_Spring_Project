<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---- Category-aware fallback images ---- */
function menuFallbackImg(string $cat): string {
    $map = [
        'burger'  => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=75',
        'pizza'   => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&q=75',
        'noodle'  => 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=500&q=75',
        'pasta'   => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=500&q=75',
        'chicken' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=500&q=75',
        'rice'    => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=500&q=75',
        'dessert' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&q=75',
        'cake'    => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&q=75',
        'salad'   => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=75',
        'healthy' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=75',
        'soup'    => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&q=75',
        'bbq'     => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500&q=75',
        'seafood' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=500&q=75',
        'drink'   => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=75',
        'ice'     => 'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=500&q=75',
        'snack'   => 'https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=500&q=75',
    ];
    $catLow = strtolower($cat);
    foreach ($map as $key => $url) {
        if (str_contains($catLow, $key)) return $url;
    }
    return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=75';
}

if (!isset($items) || !is_array($items)) { $items = []; }

/* Build unique category list for filter tabs */
$categories = ['All'];
foreach ($items as $item) {
    if (!in_array($item['category_name'], $categories)) {
        $categories[] = $item['category_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu — BiteRush</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
    <style>
        /* ===== MENU PAGE STYLES ===== */

        /* Search bar */
        .mn-search-wrap {
            max-width: 560px;
            margin: 0 auto 32px;
            position: relative;
        }
        .mn-search-wrap input {
            width: 100%;
            padding: 15px 20px 15px 52px;
            border: 2px solid #dde3f0;
            border-radius: 40px;
            font-size: 1rem;
            color: #0a0a0a;
            background: white;
            outline: none;
            box-shadow: 0 4px 16px rgba(0,0,0,.07);
            transition: border-color .2s, box-shadow .2s;
        }
        .mn-search-wrap input:focus {
            border-color: #1565c0;
            box-shadow: 0 4px 20px rgba(21,101,192,.15);
        }
        .mn-search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.15rem;
            pointer-events: none;
        }

        /* Category filter tabs */
        .mn-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 36px;
        }
        .mn-filter-btn {
            padding: 8px 20px;
            border-radius: 25px;
            border: 2px solid #dde3f0;
            background: white;
            font-size: .88rem;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all .18s;
        }
        .mn-filter-btn:hover {
            border-color: #1565c0;
            color: #1565c0;
        }
        .mn-filter-btn.active {
            background: #1565c0;
            border-color: #1565c0;
            color: white;
            box-shadow: 0 4px 12px rgba(21,101,192,.3);
        }

        /* Results count */
        .mn-results-label {
            font-size: .88rem;
            color: #777;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .mn-results-label strong { color: #0a0a0a; }

        /* Card grid */
        .mn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }

        /* Individual card */
        .mn-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            transition: transform .22s, box-shadow .22s;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        .mn-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,0,0,.13);
        }

        /* Card image */
        .mn-card-img {
            position: relative;
            height: 190px;
            overflow: hidden;
            background: #e8f0fe;
        }
        .mn-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }
        .mn-card:hover .mn-card-img img { transform: scale(1.07); }

        /* Category badge on image */
        .mn-card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(13,27,42,.75);
            color: white;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        /* Unavailable overlay */
        .mn-card-unavailable {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.55);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            color: white;
            letter-spacing: .5px;
        }

        /* Card body */
        .mn-card-body {
            padding: 16px 18px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .mn-card-body h3 {
            font-size: 1rem;
            font-weight: 800;
            color: #0a0a0a;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .mn-card-body p {
            font-size: .83rem;
            color: #666;
            line-height: 1.55;
            flex: 1;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Footer row */
        .mn-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .mn-card-price {
            font-size: 1.15rem;
            font-weight: 900;
            color: #0a0a0a;
        }
        .mn-card-price span {
            font-size: .78rem;
            font-weight: 400;
            color: #999;
        }
        .mn-add-btn {
            background: linear-gradient(135deg, #0d47a1, #1565c0);
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 25px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 3px 10px rgba(21,101,192,.3);
            white-space: nowrap;
        }
        .mn-add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(21,101,192,.4);
        }
        .mn-add-btn:active { transform: scale(.96); }

        /* Empty state */
        .mn-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 72px 20px;
            color: #999;
        }
        .mn-empty-icon { font-size: 3.5rem; margin-bottom: 14px; }
        .mn-empty h3 { font-size: 1.2rem; font-weight: 700; color: #444; margin-bottom: 8px; }
        .mn-empty p  { font-size: .92rem; }

        /* Section wrapper */
        .mn-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px 60px;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<!-- ===== PAGE HERO ===== -->
<div style="background:linear-gradient(135deg,#0a0a0a 0%,#0d1b2a 50%,#1565c0 100%);padding:52px 40px 48px;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;background:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&q=60') center/cover;opacity:.08;"></div>
    <div style="position:relative;z-index:1;">
        <span style="display:inline-block;background:rgba(255,255,255,.18);color:white;font-size:.75rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:5px 16px;border-radius:20px;margin-bottom:16px;backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.2);">🍽️ Fresh Daily</span>
        <h1 style="color:white;font-size:2.4rem;font-weight:900;margin-bottom:12px;line-height:1.2;text-shadow:0 2px 12px rgba(0,0,0,.3);">Our Full Menu</h1>
        <p style="color:#bbdefb;font-size:1rem;max-width:480px;margin:0 auto;line-height:1.7;">Handcrafted dishes made fresh every day. Find your favourite and add it to your cart.</p>
    </div>
</div>

<!-- ===== SEARCH, FILTERS & GRID ===== -->
<div class="mn-wrap">

    <!-- Search -->
    <div class="mn-search-wrap">
        <span class="mn-search-icon">🔍</span>
        <input type="text" id="searchInput" placeholder="Search dishes, categories…" autocomplete="off">
    </div>

    <!-- Category filter tabs -->
    <div class="mn-filters" id="filterTabs">
        <?php foreach ($categories as $i => $cat): ?>
            <button class="mn-filter-btn <?= $i === 0 ? 'active' : '' ?>"
                    data-cat="<?= htmlspecialchars($cat) ?>">
                <?= htmlspecialchars($cat) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Results count -->
    <div class="mn-results-label" id="resultsLabel">
        Showing <strong><?= count($items) ?></strong> item<?= count($items) !== 1 ? 's' : '' ?>
    </div>

    <!-- Card grid -->
    <div id="menuContainer" class="mn-grid">

        <?php foreach ($items as $item):
            $imgSrc = !empty($item['image_path'])
                ? '/WTech Project/public/uploads/menu/' . htmlspecialchars($item['image_path'])
                : menuFallbackImg($item['category_name']);
            $available = !empty($item['is_available']);
        ?>
        <div class="mn-card" data-cat="<?= htmlspecialchars($item['category_name']) ?>">
            <div class="mn-card-img">
                <img src="<?= $imgSrc ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=70'">
                <span class="mn-card-badge"><?= htmlspecialchars($item['category_name']) ?></span>
                <?php if (!$available): ?>
                    <div class="mn-card-unavailable">🚫 Unavailable</div>
                <?php endif; ?>
            </div>
            <div class="mn-card-body">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?></p>
                <div class="mn-card-footer">
                    <div class="mn-card-price">
                        ৳<?= number_format($item['price'], 0) ?>
                        <span>BDT</span>
                    </div>
                    <?php if ($available): ?>
                        <button class="mn-add-btn add-to-cart" data-id="<?= $item['id'] ?>">+ Add</button>
                    <?php else: ?>
                        <button class="mn-add-btn" disabled style="background:#ccc;box-shadow:none;cursor:not-allowed;">Sold Out</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($items)): ?>
        <div class="mn-empty">
            <div class="mn-empty-icon">🍽️</div>
            <h3>No items available</h3>
            <p>Check back soon — new dishes are added regularly.</p>
        </div>
        <?php endif; ?>

    </div><!-- /mn-grid -->

</div><!-- /mn-wrap -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script src="/WTech Project/public/js/cart.js"></script>
<script src="/WTech Project/public/js/menu_search.js"></script>

<script>
/* ---- Category filter tabs (client-side) ---- */
(function () {
    const filterBtns    = document.querySelectorAll('.mn-filter-btn');
    const label         = document.getElementById('resultsLabel');
    const searchBox     = document.getElementById('searchInput');

    function applyFilter(cat) {
        const cards = document.querySelectorAll('#menuContainer .mn-card');
        let visible = 0;
        cards.forEach(function (card) {
            const show = cat === 'All' || card.dataset.cat === cat;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (label) {
            label.innerHTML = 'Showing <strong>' + visible + '</strong> item' + (visible !== 1 ? 's' : '');
        }
    }

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (searchBox) searchBox.value = '';
            filterBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            applyFilter(btn.dataset.cat);
        });
    });

    if (searchBox) {
        searchBox.addEventListener('keyup', function () {
            filterBtns.forEach(function (b) { b.classList.remove('active'); });
            const allBtn = document.querySelector('.mn-filter-btn[data-cat="All"]');
            if (allBtn) allBtn.classList.add('active');
        });
    }
})();
</script>

</body>
</html>