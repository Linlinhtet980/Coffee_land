<?php
// =============================================
// app/views/items.view.php — VIEW LAYER (HTML)
// =============================================
// ဤ file သည် HTML ကိုသာ ပြသည်။
// Logic မပါ၊ Controller မှ ပေးလိုက်သော
// variable များကိုသာ ပြသည်။
// =============================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Items Management</title>
  <link rel="stylesheet" href="<?= ASSET_URL ?>/css/admin/items.css">
  <link rel="stylesheet" href="<?= ASSET_URL ?>/css/admin/sidebar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="<?= ASSET_URL ?>/js/script.js"></script>
</head>
<body>
  <div class="dashboard-container">
    <?php include BASE_PATH . '/includes/sidebar.php'; ?>

    <main class="main-content">
      <!-- Header -->
      <header class="top-header">
        <div class="header-left">
          <h1 class="page-title">Menu Items</h1>
          <button class="btn btn-primary" onclick="openItemModal()">
            <i class="fas fa-plus"></i> Add New Item
          </button>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <i class="fas fa-search text-muted"></i>
            <input type="text" placeholder="Search">
          </div>
          <div class="user-profile">
            <div class="notification-icon" onclick="toggleAdminNoti(event)">
              <i class="fas fa-bell"></i>
              <span class="badge">1</span>
              <!-- Notification Dropdown -->
              <div class="admin-dropdown" id="adminNotiDropdown" style="display: none;">
                  <div class="dropdown-header">Notifications</div>
                  <div class="dropdown-item">New order #1024 received.</div>
                  <div class="dropdown-item" style="text-align: center; color: var(--primary-color);">View All</div>
              </div>
            </div>
            <div class="profile-wrapper" onclick="toggleAdminProfile(event)">
              <img src="<?= isset($_SESSION['user_image']) && $_SESSION['user_image'] ? ASSET_URL . '/images/profiles/' . $_SESSION['user_image'] : 'https://i.pravatar.cc/150?img=11' ?>" alt="Admin" class="avatar">
              <!-- Profile Dropdown -->
              <div class="admin-dropdown" id="adminProfileDropdown" style="display: none;">
                  <div class="dropdown-header"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                  <a href="#" class="dropdown-item" onclick="openProfileModal(event)">
                      <i class="fas fa-user-circle"></i> My Profile
                  </a>
                  <a href="<?= APP_URL ?>/logout" class="dropdown-item" style="color: #e74c3c;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Items List -->
      <div class="items-list-container">
        <!-- Column Headers -->
        <div class="list-header">
          <div class="col-item">Menu Item</div>
          <div class="col-category">Category</div>
          <div class="col-price">Normal Price</div>
          <div class="col-event-price">Event Price</div>
          <div class="col-points">Reward Points</div>
          <div class="col-event">Event</div>
          <div class="col-status">Status</div>
          <div class="col-actions">Actions</div>
        </div>

        <?php if (empty($items)): ?>
          <div style="padding:20px;text-align:center;color:#6b7280;font-weight:500;background:white;border-radius:12px;border:1px solid #e5e7eb;">
            No items found in the database.
          </div>
        <?php else: ?>
          <?php foreach ($items as $item):
            $cat_name = strtolower($item['category_name'] ?? '');
            if      (strpos($cat_name, 'juice')  !== false) $badge_class = 'badge-blue';
            elseif  (strpos($cat_name, 'tea')    !== false) $badge_class = 'badge-green';
            elseif  (strpos($cat_name, 'coffee') !== false || strpos($cat_name, 'latte') !== false) $badge_class = 'badge-espresso';
            else    $badge_class = 'badge-brown';
          ?>
          <div class="item-row">
            <div class="col-item item-info">
              <span class="item-name"><?= htmlspecialchars($item['item_name']) ?></span>
            </div>
            <div class="col-category">
              <span class="badge-category <?= $badge_class ?>">
                <?= $item['category_name'] ? htmlspecialchars($item['category_name']) : 'Uncategorized' ?>
              </span>
            </div>
            <div class="col-price old-price"><?= number_format($item['n_price']) ?> Ks</div>
            <div class="col-event-price new-price">
              <?= $item['e_price'] ? number_format($item['e_price']) . ' Ks' : '-' ?>
            </div>
            <div class="col-points point-text"><?= htmlspecialchars($item['point_reward']) ?> Pts</div>
            <div class="col-event">
              <?php if ($item['event_name']): ?>
                <span class="badge-status" style="background-color:#fef3c7;color:#b45309;display:inline-flex;align-items:center;gap:4px;">
                  <i class="fas fa-tag" style="font-size:10px;"></i> <?= htmlspecialchars($item['event_name']) ?>
                </span>
              <?php else: ?>
                <span style="color:#9ca3af;font-size:14px;">-</span>
              <?php endif; ?>
            </div>
            <div class="col-status">
              <?php if ($item['status'] == 1): ?>
                <span class="badge-status badge-active">Active</span>
              <?php else: ?>
                <span class="badge-status" style="background-color:#fee2e2;color:#b91c1c;">Inactive</span>
              <?php endif; ?>
            </div>
            <div class="col-actions action-icons">
              <button class="icon-btn edit-btn" onclick="openItemModal(
                  <?= $item['id'] ?>,
                  '<?= htmlspecialchars(addslashes($item['item_name'])) ?>',
                  '<?= $item['cate_id'] ?>',
                  '<?= $item['event_id'] ?>',
                  <?= $item['n_price'] ?>,
                  <?= $item['e_price'] ?>,
                  <?= $item['point_reward'] ?>,
                  <?= $item['status'] ?>
              )"><i class="fas fa-edit"></i></button>
              <button class="icon-btn delete-btn" onclick="deleteItem(<?= $item['id'] ?>)">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($total_rows > 0): ?>
      <div class="pagination-footer">
        <div class="showing-text">
          Showing <strong><?= $offset + 1 ?>-<?= min($offset + $limit, $total_rows) ?></strong>
          of <strong><?= $total_rows ?></strong>
        </div>
        <div class="pagination-controls">
          <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="page-link"><i class="fas fa-chevron-left"></i></a>
          <?php else: ?>
            <span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-link <?= ($i == $page) ? 'active' : '' ?>"
               style="<?= ($i == $page) ? 'background-color:var(--primary-color);color:white;' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>

          <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="page-link"><i class="fas fa-chevron-right"></i></a>
          <?php else: ?>
            <span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

    </main>
  </div>

  <?php include BASE_PATH . '/includes/items_modal.php'; ?>
  <?php include BASE_PATH . '/includes/profile_modal.php'; ?>
</body>
</html>
