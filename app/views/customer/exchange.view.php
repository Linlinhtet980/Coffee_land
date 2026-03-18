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
            <li><a href="<?= APP_URL ?>/home#about">About</a></li>
            <li><a href="<?= APP_URL ?>/home#contact">Contact</a></li>
        </ul>

        <div class="nav-right">
            <div class="show_point" id="userPointsDisplay">
                <i class="fa-solid fa-star" style="color: #f1c40f; font-size: 0.8rem;"></i>
                <p><?= $_SESSION['user_point'] ?? 0 ?> Points</p>
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
