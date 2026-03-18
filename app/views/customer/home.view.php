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
            <li><a href="#">Home</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="show_point" onclick="location.href='<?= APP_URL ?>/exchange'" style="cursor: pointer;" title="Click to exchange points">
                    <i class="fa-solid fa-star" style="color: #f1c40f; font-size: 0.8rem;"></i>
                    <p><?= $_SESSION['user_point'] ?? 0 ?> Points</p>
                </div>

                <div class="noti-order">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge">0</span>
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
            <button class="auth-btn" onclick="location.reload()">Great!</button>
        </div>
    </div>

    <script>
        let cart = [];

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
            const badge = document.querySelector('.noti-order .badge');
            badge.style.transform = 'scale(1.5)';
            setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }

        function updateCartUI() {
            const count = cart.reduce((acc, item) => acc + item.quantity, 0);
            document.querySelector('.noti-order .badge').innerText = count;

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
                        <div>
                            <h4 style="margin:0; font-size: 1rem;">${item.name}</h4>
                            <small style="color: var(--text-dim);">${item.quantity} x ${item.price.toLocaleString()} Ks</small>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: var(--primary);">${subtotal.toLocaleString()} Ks</div>
                            <small style="color: #2ecc71;">+${points} Pts</small>
                        </div>
                        <button onclick="removeFromCart(${index})" style="background:none; border:none; color:#e74c3c; cursor:pointer; margin-left:10px;">
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

        function toggleCart() {
            const modal = document.getElementById('cartModal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }

        document.querySelector('.noti-order').onclick = toggleCart;

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