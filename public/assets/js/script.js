// === Sidebar Toggle & State Persistence ===

// Sidebar အဖွင့်အပိတ် (Collapse) လုပ်ရန်
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    sidebar.classList.toggle('collapsed');
    
    // အခြေအနေကို Browser (localStorage) ထဲမှာ သိမ်းထားမည်
    if (sidebar.classList.contains('collapsed')) {
        localStorage.setItem('sidebarState', 'collapsed');
    } else {
        localStorage.setItem('sidebarState', 'expanded');
    }
}

// === Item Management Modals (Add/Edit/Delete) ===

// Modal open function: Add (အသစ်ထည့်) သို့မဟုတ် Edit (ပြင်ဆင်) ခလုတ်များကို နှိပ်သည့်အခါ အလုပ်လုပ်မည်
function openItemModal(id = null, name = '', cate_id = '', event_id = '', n_price = 0, e_price = 0, points = 0, status = 1) {
    const modal = document.getElementById('itemModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('itemForm');
    
    if (!modal) return;

    if (id) {
        // Edit Mode
        if (title) title.innerText = "Edit Menu Item";
        document.getElementById('form_item_id').value = id;
        document.getElementById('form_name').value = name;
        document.getElementById('form_cate_id').value = cate_id;
        document.getElementById('form_event_id').value = event_id;
        document.getElementById('form_n_price').value = n_price;
        document.getElementById('form_e_price').value = e_price;
        document.getElementById('form_point_reward').value = points;
        document.getElementById('form_status').value = status;
    } else {
        // Add Mode
        if (title) title.innerText = "Add New Item";
        if (form) form.reset();
        document.getElementById('form_item_id').value = '';
    }
    
    modal.style.display = 'flex';
}

// Form Modal ပြန်ပိတ်ရန်
function closeItemModal() {
    const modal = document.getElementById('itemModal');
    if (modal) modal.style.display = 'none';
}

// ပစ္စည်းဖျက်ရန် (Delete)
function deleteItem(id) {
    if (confirm("Are you sure you want to delete this menu item? This action cannot be undone.")) {
        const deleteIdField = document.getElementById('delete_item_id');
        const deleteForm = document.getElementById('deleteForm');
        if (deleteIdField && deleteForm) {
            deleteIdField.value = id;
            deleteForm.submit();
        }
    }
}

// === Exchange Management Modals ===

function openExchangeModal(id = null, name = '', points = 0, stock = '0') {
    const modal = document.getElementById('exchangeModal');
    const title = document.getElementById('exchangeModalTitle');
    const form = document.getElementById('exchangeForm');

    if (!modal) return;

    if (id) {
        // Edit Mode
        if (title) title.innerText = "Edit Exchange Item";
        document.getElementById('ex_form_id').value = id;
        document.getElementById('ex_form_name').value = name;
        document.getElementById('ex_form_points').value = points;
        document.getElementById('ex_form_stock').value = stock;
    } else {
        // Add Mode
        if (title) title.innerText = "Add Exchange Item";
        if (form) form.reset();
        document.getElementById('ex_form_id').value = '';
    }

    modal.style.display = 'flex';
}

function closeExchangeModal() {
    const modal = document.getElementById('exchangeModal');
    if (modal) modal.style.display = 'none';
}

function deleteExchangeItem(id) {
    if (confirm("Are you sure you want to delete this exchange item?")) {
        const deleteIdField = document.getElementById('ex_delete_id');
        const deleteForm = document.getElementById('exDeleteForm');
        if (deleteIdField && deleteForm) {
            deleteIdField.value = id;
            deleteForm.submit();
        }
    }
}

// Modal Box အပြင်ဘက်ကို နှိပ်မိလျှင် အလိုအလျောက် ပိတ်သွားစေရန် (Update for both modals)
window.onclick = function(event) {
    const itemModal = document.getElementById('itemModal');
    const exchangeModal = document.getElementById('exchangeModal');
    
    if (event.target == itemModal) {
        closeItemModal();
    }
    if (event.target == exchangeModal) {
        closeExchangeModal();
    }
}
