<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteRush — Food Delivery Bangladesh</title>
    <link rel="stylesheet" href="/WTech Project/public/css/style.css">
    <link rel="stylesheet" href="/WTech Project/public/css/home.css">
</head>
<body class="fp-body">

<?php include __DIR__ . '/../views/partials/navbar.php'; ?>

<!-- ========== HERO ========== -->
<section class="fp-hero">
    <div class="fp-hero-inner">
        <div class="fp-hero-text">
            <h1>Order food &amp; groceries.<br><span>Delivered fast.</span></h1>
            <p>Fresh from the best restaurants in town — right to your door in minutes.</p>
            <a href="/WTech Project/routes/web.php" class="fp-hero-btn">Find Food Near You</a>
        </div>
        <div class="fp-hero-img">
            <img src="https://images.unsplash.com/photo-1585238342024-78d387f4a707?w=640&q=80"
                 alt="Delivery bag with food">
        </div>
    </div>
    <!-- Wave divider -->
    <div class="fp-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#f5f5f5"/>
        </svg>
    </div>
</section>

<!-- ========== CATEGORY BUBBLES ========== -->
<section class="fp-section fp-cats-section">
    <h2 class="fp-section-title">What are you craving?</h2>
    <div class="fp-cats">
        <?php
        $cats = [
            ['https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=200&q=70', 'Burgers'],
            ['https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&q=70', 'Pizza'],
            ['https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=200&q=70', 'Noodles'],
            ['https://images.unsplash.com/photo-1562967914-608f82629710?w=200&q=70', 'Chicken'],
            ['https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200&q=70', 'Healthy'],
            ['https://images.unsplash.com/photo-1551024601-bec78aea704b?w=200&q=70', 'Desserts'],
            ['https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=200&q=70', 'Ice Cream'],
            ['https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=200&q=70', 'BBQ'],
        ];
        foreach ($cats as $cat): ?>
        <a href="/WTech Project/routes/web.php" class="fp-cat-item">
            <div class="fp-cat-img">
                <img src="<?= $cat[0] ?>" alt="<?= $cat[1] ?>">
            </div>
            <span><?= $cat[1] ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ========== PROMO BANNERS ========== -->
<section class="fp-section">
    <div class="fp-banners">
        <a href="/WTech Project/routes/web.php" class="fp-banner fp-banner-1">
            <div class="fp-banner-text">
                <span class="fp-banner-tag">🔥 New User Offer</span>
                <h3>Free delivery<br>on your first order</h3>
                <span class="fp-banner-cta">Order now →</span>
            </div>
            <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=70" alt="Offer">
        </a>
        <a href="/WTech Project/routes/web.php" class="fp-banner fp-banner-2">
            <div class="fp-banner-text">
                <span class="fp-banner-tag">⚡ Express</span>
                <h3>30-minute<br>delivery guarantee</h3>
                <span class="fp-banner-cta">Explore →</span>
            </div>
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&q=70" alt="Express">
        </a>
        <a href="/WTech Project/routes/web.php" class="fp-banner fp-banner-3">
            <div class="fp-banner-text">
                <span class="fp-banner-tag">🛒 Grocery</span>
                <h3>Fresh groceries<br>delivered daily</h3>
                <span class="fp-banner-cta">Shop now →</span>
            </div>
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=70" alt="Grocery">
        </a>
    </div>
</section>

<!-- ========== POPULAR RESTAURANTS / ITEMS ========== -->
<section class="fp-section">
    <div class="fp-section-header">
        <h2 class="fp-section-title" style="margin-bottom:0">Popular right now 🔥</h2>
        <a href="/WTech Project/routes/web.php" class="fp-see-all">See all →</a>
    </div>
    <div class="fp-cards">
        <?php
        $items = [
            ['https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=75', 'Classic Smash Burger',   'Burgers',    '৳180', '4.8', '25–35'],
            ['https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&q=75', 'Margherita Pizza',       'Pizza',      '৳320', '4.7', '30–40'],
            ['https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&q=75', 'Spicy Ramen Bowl',       'Noodles',    '৳220', '4.6', '20–30'],
            ['https://images.unsplash.com/photo-1562967914-608f82629710?w=400&q=75', 'Crispy Fried Chicken',   'Chicken',    '৳250', '4.9', '25–35'],
            ['https://images.unsplash.com/photo-1551024601-bec78aea704b?w=400&q=75', 'Chocolate Lava Cake',    'Desserts',   '৳150', '4.8', '15–25'],
            ['https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=75', 'Garden Fresh Salad',     'Healthy',    '৳160', '4.5', '15–20'],
        ];
        foreach ($items as $item): ?>
        <a href="/WTech Project/routes/web.php" class="fp-card">
            <div class="fp-card-img">
                <img src="<?= $item[0] ?>" alt="<?= $item[1] ?>">
                <span class="fp-card-time">⏱ <?= $item[5] ?> min</span>
            </div>
            <div class="fp-card-body">
                <div class="fp-card-cat"><?= $item[2] ?></div>
                <h3><?= $item[1] ?></h3>
                <div class="fp-card-meta">
                    <span class="fp-card-price"><?= $item[3] ?></span>
                    <span class="fp-card-rating">⭐ <?= $item[4] ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- ========== HOW IT WORKS ========== -->
<section class="fp-how">
    <div class="fp-section">
        <h2 class="fp-section-title">How BiteRush works</h2>
        <div class="fp-steps">
            <div class="fp-step">
                <div class="fp-step-icon">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=160&q=70" alt="Browse">
                </div>
                <div class="fp-step-num">01</div>
                <h3>Browse &amp; choose</h3>
                <p>Explore hundreds of dishes from top-rated restaurants near you.</p>
            </div>
            <div class="fp-step-arrow">→</div>
            <div class="fp-step">
                <div class="fp-step-icon">
                    <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=160&q=70" alt="Order">
                </div>
                <div class="fp-step-num">02</div>
                <h3>Place your order</h3>
                <p>Add to cart, enter your address and pay securely in seconds.</p>
            </div>
            <div class="fp-step-arrow">→</div>
            <div class="fp-step">
                <div class="fp-step-icon">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=160&q=70" alt="Deliver">
                </div>
                <div class="fp-step-num">03</div>
                <h3>Fast delivery</h3>
                <p>Sit back and relax. Your food arrives hot and fresh at your door.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== ARTICLE / ABOUT ========== -->
<section class="fp-about">
    <div class="fp-section fp-about-grid">
        <div class="fp-about-img">
            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=700&q=80" alt="About">
            <div class="fp-about-badge">🍴 Serving Dhaka since 2020</div>
        </div>
        <div class="fp-about-text">
            <span class="fp-tag-label">Our Story</span>
            <h2>Fast food delivery<br>in <span>Dhaka</span></h2>
            <p>As the bustling capital of Bangladesh, it's no wonder that Dhaka's culinary scene is something special. With BiteRush, you have easy access to this diverse dining scene — straightforward online ordering and express delivery so you can enjoy eating in style.</p>
            <p>Whether it's a brilliant brunch or last-minute lunch, mouth-watering dinner or late-night feast, you've plenty to pick from. We partner with the best local kitchens to bring you fresh, flavourful dishes.</p>
            <div class="fp-about-stats">
                <div><strong>2,000+</strong><span>Restaurants</span></div>
                <div><strong>30 min</strong><span>Avg. Delivery</span></div>
                <div><strong>4.8 ★</strong><span>Rating</span></div>
            </div>
            <a href="/WTech Project/routes/web.php" class="fp-pink-btn">Order Now</a>
        </div>
    </div>
</section>

<!-- ========== APP DOWNLOAD STRIP ========== -->
<section class="fp-app-strip">
    <div class="fp-section fp-app-grid">
        <div class="fp-app-text">
            <h2>Order even faster<br>with the <span>BiteRush app</span></h2>
            <p>Available on iOS and Android. Track your orders in real time, save your favourites and get exclusive app-only deals.</p>
            <div class="fp-app-btns">
                <a href="#" class="fp-store-btn">
                    <span class="fp-store-icon">🍎</span>
                    <div><small>Download on the</small><strong>App Store</strong></div>
                </a>
                <a href="#" class="fp-store-btn">
                    <span class="fp-store-icon">▶</span>
                    <div><small>Get it on</small><strong>Google Play</strong></div>
                </a>
            </div>
        </div>
        <div class="fp-app-img">
            <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&q=80" alt="App">
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="fp-footer">
    <div class="fp-footer-inner">
        <div class="fp-footer-brand-col">
            <div class="fp-footer-logo">🍽️ BiteRush</div>
            <p>Fast, fresh food delivery right to your door. Order in seconds.</p>
            <div class="fp-social">
                <a href="#">📘</a>
                <a href="#">📸</a>
                <a href="#">🐦</a>
            </div>
        </div>
        <div class="fp-footer-col">
            <h4>Company</h4>
            <a href="#">About Us</a>
            <a href="#">Careers</a>
            <a href="#">Press</a>
            <a href="#">Blog</a>
        </div>
        <div class="fp-footer-col">
            <h4>Order</h4>
            <a href="/WTech Project/public/index.php">Home</a>
            <a href="/WTech Project/routes/web.php">Menu</a>
            <a href="/WTech Project/views/cart/index.php">Cart</a>
            <a href="/WTech Project/routes/my_orders.php">My Orders</a>
        </div>
        <div class="fp-footer-col">
            <h4>Account</h4>
            <a href="/WTech Project/views/auth/login.php">Login</a>
            <a href="/WTech Project/views/auth/register.php">Register</a>
            <a href="/WTech Project/views/profile/profile.php">Profile</a>
        </div>
        <div class="fp-footer-col">
            <h4>Support</h4>
            <a href="#">Help Center</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
    <div class="fp-footer-bottom">
        <span>&copy; <?= date('Y') ?> BiteRush. All rights reserved.</span>
        <span>Made with ❤️ in Bangladesh</span>
    </div>
</footer>

</body>
</html>