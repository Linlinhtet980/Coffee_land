<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Coffee Land - Rewards Exchange</title>
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/customer/home.view.css">
    <style>
        .exchange-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(17.5rem, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }
        .redeem-btn {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
        }
        .redeem-btn:hover {
            box-shadow: 0 0.5rem 1.5625rem rgba(46, 204, 113, 0.4) !important;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <i class="fa-solid fa-coffee"></i>
            <span>Coffee Land</span>
        </div>

        <ul>
            <li><a href="<?= APP_URL ?>/home">Home</a></li>
            <li><a href="<?= APP_URL ?>/home#menu">Menu</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-right">
            <div class="show_point" id="userPointsDisplay">
                <i class="fa-solid fa-star" style="color: #f1c40f; font-size: 0.8rem;"></i>
                <p><?= $_SESSION['user_point'] ?? 0 ?> Points</p>
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

            <div class="profile" onclick="location.href='<?= APP_URL ?>/logout'" title="Logout">
                <i class="fa-solid fa-circle-user"></i>
                <p class="name"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
            </div>
        </div>
    </nav>

    <section class="hero" style="height: 40vh; margin-top: 5rem;">
        <h1>Redeem Your Points</h1>
        <p>Exchange your hard-earned points for exciting rewards and exclusive items.</p>
    </section>

    <section class="menu-section">
        <div class="section-header">
            <h2>Available Rewards</h2>
            <p>Special gifts carefully selected for you.</p>
        </div>

        <div class="exchange-grid">
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="product-card">
                        <div class="card-image">
                            <span class="category-tag">Reward</span>
                            <i class="fa-solid fa-gift"></i>
                        </div>
                        <div class="item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <div class="reward-tag" style="color: #e67e22;">
                                <i class="fa-solid fa-star"></i>
                                <?= number_format($item['point_required']) ?> Pts Required
                            </div>
                            <div class="price-row">
                                <span style="color: var(--text-dim); font-size: 0.9rem;">
                                    Balance: <?= htmlspecialchars($item['stock_qty']) ?> pieces
                                </span>
                            </div>
                            <button class="add-btn redeem-btn" 
                                    onclick="redeemItem(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['point_required'] ?>)"
                                    <?= (int)$item['stock_qty'] <= 0 ? 'disabled' : '' ?>>
                                <?= (int)$item['stock_qty'] <= 0 ? 'Out of Stock' : 'Redeem Now' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; grid-column: 1/-1;">
                    <p style="color: var(--text-dim);">No rewards available at the moment.</p>
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
                    <li><a href="<?= APP_URL ?>/home">Home</a></li>
                    <li><a href="<?= APP_URL ?>/home#menu">Our Menu</a></li>
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

    <!-- Success/Error Modal -->
    <div class="modal-overlay" id="statusModal">
        <div class="modal-box" style="text-align: center; padding: 3rem; max-width: 25rem;">
            <div id="statusIcon" style="font-size: 4rem; margin-bottom: 1.5rem;"></div>
            <h2 id="statusTitle" style="margin-bottom: 1rem;"></h2>
            <p id="statusMessage" style="color: var(--text-dim); margin-bottom: 2rem;"></p>
            <button class="auth-btn" onclick="closeStatusModal()">OK</button>
        </div>
    </div>

    <script>
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
                    fetchNotifications();
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

        document.addEventListener('DOMContentLoaded', fetchNotifications);

        async function redeemItem(id, name, pointsRequired) {
            if (!confirm(`Are you sure you want to exchange '${name}' for ${pointsRequired} points?`)) return;

            try {
                const response = await fetch('<?= APP_URL ?>/exchange', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ itemId: id })
                });

                const result = await response.json();
                
                const modal = document.getElementById('statusModal');
                const icon = document.getElementById('statusIcon');
                const title = document.getElementById('statusTitle');
                const msg = document.getElementById('statusMessage');

                if (result.success) {
                    icon.innerHTML = '<i class="fas fa-check-circle" style="color: #2ecc71;"></i>';
                    title.innerText = 'Success!';
                    msg.innerText = result.message;
                    // Update points in navbar
                    document.querySelector('#userPointsDisplay p').innerText = result.newPoints + ' Points';
                } else {
                    icon.innerHTML = '<i class="fas fa-times-circle" style="color: #e74c3c;"></i>';
                    title.innerText = 'Redemption Failed';
                    msg.innerText = result.message;
                }

                modal.style.display = 'flex';
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
        }

        function closeStatusModal() {
            document.getElementById('statusModal').style.display = 'none';
            location.reload();
        }
    </script>
</body>

</html>
