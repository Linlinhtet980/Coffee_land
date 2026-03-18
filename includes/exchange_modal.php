<!-- Exchange Form Modal (Add/Edit) -->
<div class="modal-overlay" id="exchangeModal">
    <div class="modal-box">
        <div class="modal-header">
            <h2 id="exchangeModalTitle">Add Exchange Item</h2>
            <button class="close-btn" onclick="closeExchangeModal()"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= APP_URL ?>/admin/exchange" method="POST" id="exchangeForm">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="item_id" id="ex_form_id" value="">
            
            <div class="form-group">
                <label for="ex_form_name">Item Name</label>
                <input type="text" id="ex_form_name" name="name" class="form-control" required placeholder="e.g., 10% Discount Coupon">
            </div>

            <div class="form-row" style="display:flex; gap:16px;">
                <div class="form-group" style="flex:1;">
                    <label for="ex_form_points">Points Required</label>
                    <input type="number" id="ex_form_points" name="point_required" class="form-control" value="0" step="0.01" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label for="ex_form_stock">Stock Quantity</label>
                    <input type="text" id="ex_form_stock" name="stock_qty" class="form-control" value="0" required placeholder="e.g., 100">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" style="background:var(--bg-color); color:var(--text-main);" onclick="closeExchangeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="exDeleteForm" action="<?= APP_URL ?>/admin/exchange" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="item_id" id="ex_delete_id" value="">
</form>
