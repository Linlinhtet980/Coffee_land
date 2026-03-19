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

    <!-- Cart Modal -->
    <div class="modal-overlay" id="cartModal">
        <div class="modal-box" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Your Order</h2>
                <button class="close-btn" onclick="toggleCart()"><i class="fas fa-times"></i></button>
            </div>
            <div class="cart-items" id="cartItemsList">
                <!-- Cart items will be injected here -->
            </div>
            <div class="cart-summary" style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-dim);">Total Amount</span>
                    <span class="price" id="totalAmount">0 Ks</span>
                </div>
                <div style="display: flex; justify-content: space-between; color: #2ecc71; font-weight: 600;">
                    <span>Total Points Reward</span>
                    <span id="totalPoints">0 Pts</span>
                </div>
                <button class="auth-btn" id="confirmOrderBtn" onclick="processCheckout()">
                    <i class="fas fa-shopping-bag"></i> Confirm Order
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="modal-box" style="text-align: center; padding: 3rem;">
            <div style="font-size: 4rem; color: #2ecc71; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 1rem;">Order Successful!</h2>
            <p style="color: var(--text-dim); margin-bottom: 2rem;">Your points have been added to your account.</p>
            <button class="auth-btn" onclick="document.getElementById('successModal').style.display='none'">Great!</button>
        </div>
    </div>



    <script>
        let cart = [];
        let notiDropdownOpen = false;

        async function fetchNotifications() {
            try {
                const res = await fetch('<?= APP_URL ?>/notifications/get');
                const result = await res.json();
                if (result.success) {
                    const badge = document.getElementById('notiBadge');
                    if (result.unreadCount > 0) {
                        badge.innerText = result.unreadCount;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }

                    const list = document.getElementById('notiList');
                    if (result.notifications.length === 0) {
                        list.innerHTML = '<p style="text-align:center; color:var(--text-dim); padding: 2rem;">No notifications yet.</p>';
                        return;
                    }

                    list.innerHTML = result.notifications.map(n => `
                        <div class="noti-item ${n.is_read == 0 ? 'unread' : ''}" onclick="markNotiRead(${n.id}, event)">
                            <div class="noti-icon">
                                <i class="fas fa-${n.type === 'order' ? 'shopping-bag' : (n.type==='system' ? 'gift' : 'bell')}"></i>
                            </div>
                            <div class="noti-content">
                                <p class="noti-title">${n.title}</p>
                                <p class="noti-msg">${n.message}</p>
                                <span class="noti-time">${new Date(n.created_at).toLocaleString()}</span>
                            </div>
                        </div>
                    `).join('');
                }
            } catch (e) {
                console.error("Failed to fetch notifications", e);
            }
        }

        function toggleNotiDropdown() {
            notiDropdownOpen = !notiDropdownOpen;
            const dropdown = document.getElementById('notiDropdown');
            if (notiDropdownOpen) {
                dropdown.classList.add('show');
                fetchNotifications();
            } else {
                dropdown.classList.remove('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.querySelector('.noti-dropdown-container');
            if (container && !container.contains(e.target) && notiDropdownOpen) {
                toggleNotiDropdown();
            }
        });

        async function markNotiRead(id, e) {
            if (e) e.stopPropagation();
            try {
                const res = await fetch('<?= APP_URL ?>/notifications/read', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                });
                const result = await res.json();
                if (result.success) {
                    fetchNotifications(); // refresh list
                }
            } catch (e) { console.error(e); }
        }

        async function markAllNotiRead(e) {
            if (e) e.stopPropagation();
            try {
                const res = await fetch('<?= APP_URL ?>/notifications/read', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: 'all'})
                });
                const result = await res.json();
                if (result.success) {
                    fetchNotifications();
                }
            } catch (e) { console.error(e); }
        }

        // Initial fetch
        document.addEventListener('DOMContentLoaded', fetchNotifications);

        function addToCart(id, name, price, points) {
            <?php if (!isset($_SESSION['user_id'])): ?>
                alert('Please login to order items.');
                window.location.href = '<?= APP_URL ?>/login';
                return;
            <?php endif; ?>

            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, name, price, points, quantity: 1 });
            }
            updateCartUI();
            
            // Notification effect
            const badge = document.querySelector('.cart-icon .badge');
            badge.style.transform = 'scale(1.5)';
            setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }

        function updateCartUI() {
            const count = cart.reduce((acc, item) => acc + item.quantity, 0);
            const _badge = document.getElementById('cartBadge');
            if(count > 0) {
                _badge.innerText = count;
                _badge.style.display = 'block';
            } else {
                _badge.style.display = 'none';
            }

            const list = document.getElementById('cartItemsList');
            let html = '';
            let totalAmount = 0;
            let totalPoints = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                const points = item.points * item.quantity;
                totalAmount += subtotal;
                totalPoints += points;

                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                        <div style="flex: 1;">
                            <h4 style="margin:0; font-size: 1rem;">${item.name}</h4>
                            <small style="color: var(--text-dim);">${item.price.toLocaleString()} Ks</small>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.8rem; margin: 0 1rem;">
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)">
                                <i class="fas fa-minus" style="font-size: 0.8rem;"></i>
                            </button>
                            <span class="qty-display">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)">
                                <i class="fas fa-plus" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>

                        <div style="text-align: right; min-width: 80px;">
                            <div style="font-weight: 700; color: var(--primary);">${subtotal.toLocaleString()} Ks</div>
                            <small style="color: #2ecc71;">+${points} Pts</small>
                        </div>
                        <button onclick="removeFromCart(${index})" style="background:none; border:none; color:#e74c3c; cursor:pointer; margin-left:15px; font-size: 1.1rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });

            list.innerHTML = html || '<p style="text-align:center; color:var(--text-dim);">Your cart is empty.</p>';
            document.getElementById('totalAmount').innerText = totalAmount.toLocaleString() + ' Ks';
            document.getElementById('totalPoints').innerText = totalPoints + ' Pts';
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        function updateQuantity(index, change) {
            if (cart[index].quantity + change > 0) {
                cart[index].quantity += change;
            } else {
                removeFromCart(index);
            }
            updateCartUI();
        }

        function toggleCart() {
            const modal = document.getElementById('cartModal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }

        document.querySelector('.cart-icon').onclick = toggleCart;

        async function processCheckout() {
            if (cart.length === 0) return;
            
            const btn = document.getElementById('confirmOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            const totalPrice = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
            const totalPoints = cart.reduce((acc, item) => acc + (item.points * item.quantity), 0);

            try {
                const response = await fetch('<?= APP_URL ?>/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        items: cart,
                        totalPrice: totalPrice,
                        totalPoints: totalPoints
                    })
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById('cartModal').style.display = 'none';
                    document.getElementById('successModal').style.display = 'flex';
                    
                    fetchNotifications(); // Fetch latest notifications from DB
                    
                    if (result.new_points !== undefined) {
                        const pointDisplay = document.querySelector('.show_point p');
                        if (pointDisplay) {
                            pointDisplay.innerText = result.new_points + ' Points';
                        }
                    }

                    cart = [];
                    updateCartUI();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Confirm Order';
            }
        }
        // Category Filter Logic
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.product-card');

            function showDefault() {
                let categoryCounts = {};
                cards.forEach(card => {
                    const cat = card.getAttribute('data-category');
                    categoryCounts[cat] = (categoryCounts[cat] || 0) + 1;
                    
                    if (categoryCounts[cat] <= 6) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Initialize default view
            showDefault();

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active state
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filter = btn.getAttribute('data-filter');

                    if (filter === 'all') {
                        showDefault();
                    } else {
                        cards.forEach(card => {
                            if (card.getAttribute('data-category') === filter) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>