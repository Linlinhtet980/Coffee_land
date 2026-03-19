<?php
// =============================================
// app/views/admin/exchange.view.php
// =============================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Points Exchange Management</title>
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
          <h1 class="page-title">Points Exchange</h1>
          <button class="btn btn-primary" onclick="openExchangeModal()">
            <i class="fas fa-plus"></i> Add Exchange Item
          </button>
        </div>
        <div class="header-right">
          <div class="search-bar">
            <i class="fas fa-search text-muted"></i>
            <input type="text" placeholder="Search rewards">
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

      <!-- Exchange Items List -->
      <div class="items-list-container">
        <!-- Column Headers -->
        <div class="list-header grid-exchange">
          <div class="col-item">Reward Item</div>
          <div class="col-points">Points Required</div>
          <div class="col-event">Stock Balance</div>
          <div class="col-status">Created At</div>
          <div class="col-actions">Actions</div>
        </div>

        <?php if (empty($items)): ?>
          <div style="padding:20px;text-align:center;color:#6b7280;font-weight:500;background:white;border-radius:12px;border:1px solid #e5e7eb;">
            No exchange items found.
          </div>
        <?php else: ?>
          <?php foreach ($items as $item): ?>
          <div class="item-row grid-exchange">
            <div class="col-item item-info">
              <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
            </div>
            <div class="col-points point-text"><?= number_format($item['point_required'], 2) ?> Pts</div>
            <div class="col-event">
                <span class="badge-status" style="background-color:#e0f2fe;color:#0369a1;display:inline-flex;align-items:center;gap:4px;">
                    <?= htmlspecialchars($item['stock_qty']) ?> Units
                </span>
            </div>
            <div class="col-status">
              <span style="font-size: 14px; color: #6b7280;"><?= date('Y-m-d H:i', strtotime($item['created_at'])) ?></span>
            </div>
            <div class="col-actions action-icons">
              <button class="icon-btn edit-btn" onclick="openExchangeModal(
                  <?= $item['id'] ?>,
                  '<?= htmlspecialchars(addslashes($item['name'])) ?>',
                  <?= $item['point_required'] ?>,
                  '<?= htmlspecialchars(addslashes($item['stock_qty'])) ?>'
              )"><i class="fas fa-edit"></i></button>
              <button class="icon-btn delete-btn" onclick="deleteExchangeItem(<?= $item['id'] ?>)">
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

  <?php include BASE_PATH . '/includes/exchange_modal.php'; ?>
  <?php include BASE_PATH . '/includes/profile_modal.php'; ?>
</body>
</html>
