<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Coffee Land - Welcome</title>
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/customer/home.view.css">
</head>

<body>
    <nav>
        <div class="logo">
            <i class="fa-solid fa-coffee"></i>
            <span>Coffee Land</span>
        </div>

        <ul>
            <li><a href="<?= APP_URL ?>/">Home</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="show_point" onclick="location.href='<?= APP_URL ?>/exchange'" style="cursor: pointer;" title="Click to exchange points">
                    <i class="fa-solid fa-star" style="color: #f1c40f; font-size: 0.8rem;"></i>
                    <p><?= $_SESSION['user_point'] ?? 0 ?> Points</p>
                </div>

                <div class="cart-icon" title="Your Cart" onclick="toggleCart()">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="badge" id="cartBadge" style="display: none;">0</span>
                </div>

                <div class="noti-dropdown-container">
                    <div class="noti-order" onclick="toggleNotiDropdown()" title="Notifications">
                        <i class="fa-solid fa-bell"></i>
                        <span class="badge" id="notiBadge" style="display: none;">0</span>
                    </div>
                    
                    <div class="noti-dropdown" id="notiDropdown">
                        <div class="noti-header">
                            <h3>Notifications</h3>
                            <button onclick="markAllNotiRead(event)" class="mark-read-btn">Mark all read</button>
                        </div>
                        <div class="noti-body" id="notiList">
                            <div style="text-align: center; padding: 2rem; color: var(--text-dim);">
                                <i class="fas fa-spinner fa-spin"></i> Loading...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile" onclick="location.href='<?= APP_URL ?>/logout'" title="Click to Logout">
                    <i class="fa-solid fa-circle-user"></i>
                    <p class="name"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
                </div>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login" class="add-btn" style="padding: 0.5rem 1.5rem; margin: 0; width: auto; font-size: 0.9rem;">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <h1>Freshly Brewed Magic</h1>
        <p>Experience the finest coffee beans roasted to perfection. Your daily dose of happiness is just a click away.
        </p>
    </section>

    <section class="menu-section" id="menu">
        <div class="section-header">
            <h2>Our Signature Menu</h2>
            <p>Hand-crafted beverages made with love and passion.</p>
        </div>

        <div class="category-filter">
            <button class="filter-btn active" data-filter="all">All Items</button>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter-btn" data-filter="<?= htmlspecialchars($cat['name']) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="menu-grid">
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="product-card" data-category="<?= htmlspecialchars($item['category_name'] ?: 'Coffee') ?>">
                        <div class="card-image">
                            <span class="category-tag">
                                <?= htmlspecialchars($item['category_name'] ?: 'Coffee') ?>
                            </span>
                            <?php
                            $icon = 'fa-mug-hot';
                            $cat = strtolower($item['category_name'] ?: '');
                            if (strpos($cat, 'juice') !== false)
                                $icon = 'fa-glass-water';
                            elseif (strpos($cat, 'tea') !== false)
                                $icon = 'fa-leaf';
                            ?>
                            <i class="fa-solid <?= $icon ?>"></i>
                        </div>
                        <div class="item-details">
                            <h3>
                                <?= htmlspecialchars($item['item_name']) ?>
                            </h3>
                            <div class="reward-tag">
                                <i class="fa-solid fa-coins"></i>
                                <?= htmlspecialchars($item['point_reward']) ?> Pts Reward
                            </div>
                            <div class="price-row">
                                <div class="price-box">
                                    <?php if ($item['e_price']): ?>
                                        <span class="old-price">
                                            <?= number_format($item['n_price']) ?> Ks
                                        </span>
                                        <span class="price">
                                            <?= number_format($item['e_price']) ?> Ks
                                        </span>
                                    <?php else: ?>
                                        <span class="price">
                                            <?= number_format($item['n_price']) ?> Ks
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button class="add-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes($item['item_name']) ?>', <?= $item['e_price'] ?: $item['n_price'] ?>, <?= $item['point_reward'] ?>)">Order Now</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; grid-column: 1/-1;">
                    <p style="color: var(--text-dim);">No items available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>Our Story</h2>
                <p>At Coffee Land, we believe that every cup of coffee tells a story. From the carefully selected beans from local farms to the precise roasting process, our passion is to bring you the perfect brew. Our journey started in a small roastery with a dream to connect communities through exceptional coffee.</p>
                <div class="about-features">
                    <div class="feature">
                        <i class="fa-solid fa-leaf"></i>
                        <span>100% Organic</span>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-mug-hot"></i>
                        <span>Premium Roasts</span>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-people-carry-box"></i>
                        <span>Fair Trade</span>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="<?= ASSET_URL ?>/images/about-coffee.jpg" alt="About Coffee Land" onerror="this.src='https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&q=80&w=800';">
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="section-header">
            <h2>Get In Touch</h2>
            <p>We'd love to hear from you. Reach out to us for any inquiries.</p>
        </div>
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <h3>Location</h3>
                        <p>123 Coffee Street, Brewville, Yangon</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <h3>Phone</h3>
                        <p>+95 9 123 456 789</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>hello@coffeeland.com</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <h3>Opening Hours</h3>
                        <p>Mon-Sun: 8:00 AM - 10:00 PM</p>
                    </div>
                </div>
            </div>
            <div class="contact-form-container">
                <form class="contact-form" onsubmit="event.preventDefault(); alert('Thank you for getting in touch! We will reply soon.');">
                    <div class="form-group">
                        <input type="text" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Your Message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="add-btn" style="margin-top:0;">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="logo">
                    <i class="fa-solid fa-coffee"></i>
                    <span>Coffee Land</span>
                </div>
                <p>Bringing you the finest coffee experience, one cup at a time.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?= APP_URL ?>/">Home</a></li>
                    <li><a href="#menu">Our Menu</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Refund Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Coffee Land. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modals -->
    <?php require __DIR__ . '/components/cart_modal.php'; ?>
    <?php require __DIR__ . '/components/success_modal.php'; ?>
    <?php require __DIR__ . '/components/payment_modal.php'; ?>

    <script>
        const APP_URL = '<?= APP_URL ?>';
        const IS_LOGGED_IN = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    </script>
    <script src="<?= ASSET_URL ?>/js/customer/home.js"></script>
</body>

</html>