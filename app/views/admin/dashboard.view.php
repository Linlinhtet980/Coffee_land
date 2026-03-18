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
            <h1>Dashboard Overview</h1>
            <p>Welcome to Coffee Land Management System.</p>
        </main>
    </div>
</body>
</html>
