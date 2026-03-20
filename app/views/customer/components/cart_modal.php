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
            <button class="auth-btn" id="confirmOrderBtn" onclick="showPaymentModal()">
                <i class="fas fa-shopping-bag"></i> Confirm Order
            </button>
        </div>
    </div>
</div>
