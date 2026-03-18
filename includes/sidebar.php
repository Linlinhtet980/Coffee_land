<?php
// လက်ရှိရောက်နေသော Route ကို ယူရန်
$current_page = trim(str_replace('/coffee_land/public', '', $_SERVER['REQUEST_URI']), '/');
$current_page = preg_replace('/^index\.php\/?/', '', $current_page); // index.php ကို ဖယ်ထုတ်သည်
$current_page = explode('?', $current_page)[0]; // Query string ဖယ်ထုတ်ခြင်း
?>
<!-- ဘယ်ဘက် ဘေးတန်း (Sidebar) အပိုင်း -->
<aside class="sidebar" id="sidebar">
    <script>
        // စာမျက်နှာ Render မလုပ်ခင် Sidebar အခြေအနေကို ချက်ချင်းစစ်ဆေးပြီး အလုပ်လုပ်မည်
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    </script>
    <div class="brand" onclick="toggleSidebar()">
        <div class="brand-icon">
            <i class="fas fa-coffee coffee-icon"></i>
        </div>
        <span class="brand-text">Coffee Land</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item <?php echo ($current_page == 'admin/dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> <span class="nav-text">Dashboard</span>
        </a>
        <a href="<?= APP_URL ?>/admin/items" class="nav-item <?php echo ($current_page == 'admin/items') ? 'active' : ''; ?>">
            <i class="fas fa-mug-hot"></i> <span class="nav-text">Menu Items</span>
        </a>
        <a href="<?= APP_URL ?>/admin/exchange" class="nav-item <?php echo ($current_page == 'admin/exchange') ? 'active' : ''; ?>">
            <i class="fas fa-gift"></i> <span class="nav-text">Points Exchange</span>
        </a>
        <a href="<?= APP_URL ?>/home" class="nav-item">
            <i class="fas fa-external-link-alt"></i> <span class="nav-text">View Site</span>
        </a>
    </nav>
</aside>
