<?php
// =============================================
// app/views/dashboard.view.php
// =============================================
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/admin/items.css">
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/admin/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="<?= ASSET_URL ?>/js/script.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <?php include BASE_PATH . '/includes/sidebar.php'; ?>

        <!-- ညာဘက် အဓိက အကြောင်းအရာများ ပြသမည့် အပိုင်း -->
        <main class="main-content">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <h1 class="page-title">Dashboard Overview</h1>
                </div>
                <div class="header-right">
                    <div class="search-bar">
                        <i class="fas fa-search text-muted"></i>
                        <input type="text" placeholder="Search...">
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
            
            <div style="padding: 2rem;">
                <p>Welcome to Coffee Land Management System.</p>
            </div>
        </main>
    </div>

    <?php include BASE_PATH . '/includes/profile_modal.php'; ?>
</body>
</html>
