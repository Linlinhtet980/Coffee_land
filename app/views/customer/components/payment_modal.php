<div class="modal-overlay" id="paymentModal" style="display: none; background: rgba(0,0,0,0.6);">
    <div class="receipt-modal">
        <div class="receipt-header">
            <i class="fa-solid fa-mug-hot"></i>
            <h2>COFFEE LAND</h2>
            <p>Payment Receipt</p>
        </div>
        
        <div class="receipt-info">
            <span id="receiptDate">Date: 19 Mar 2026, 06:15 AM</span>
            <span>Customer: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?></span>
        </div>
        
        <div class="receipt-divider-dashed"></div>
        
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>ITEM</th>
                    <th class="center">QTY</th>
                    <th class="right">AMOUNT</th>
                </tr>
            </thead>
            <tbody id="receiptItems">
                <!-- JS Injected -->
            </tbody>
        </table>
        
        <div class="receipt-divider-dashed"></div>
        
        <div class="receipt-subtotal">
            <span style="color: var(--text-dim);">Subtotal</span>
            <span id="receiptSubtotal" style="color: var(--text); font-weight: 500;">0 KS</span>
        </div>
        
        <div class="receipt-divider-solid"></div>
        
        <div class="receipt-total">
            <span>Total Payable</span>
            <span id="receiptTotalPayable">0 KS</span>
        </div>
        
        <div class="payment-methods">
            <button class="method-btn active" onclick="selectPaymentMethod('Cash')">Cash</button>
            <button class="method-btn" onclick="selectPaymentMethod('KPay')">KPay</button>
            <button class="method-btn" onclick="selectPaymentMethod('WaveMoney')">WaveMoney</button>
        </div>
        
        <button class="confirm-pay-btn" id="confirmPaymentBtn" onclick="processPayment()">
            <i class="fa-solid fa-money-bill-wave"></i> Confirm & Pay
        </button>
        
        <button class="cancel-order-btn" onclick="goBackToCart()">Cancel Order</button>
    </div>
</div>
