<?php
/**
 * Checkout Page - BookHaven
 */
$pageTitle = 'Checkout';
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    redirect('index.php?page=cart');
    exit;
}

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 49.00;
$total = $subtotal + $shipping;
?>

<section class="section">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2><i class="fas fa-credit-card" style="color:var(--primary);"></i> Checkout</h2>
        </div>

        <div class="checkout-layout">
            <!-- Form -->
            <div>
                <form id="checkoutForm" method="POST" action="<?php echo BASE_URL; ?>actions/checkout.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="checkout-form-card">
                        <h3 style="margin-bottom:var(--space-xl); font-family:var(--font-main);">
                            <i class="fas fa-truck"></i> Shipping Information
                        </h3>
                        <div class="admin-form-grid">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" required data-label="Full Name"
                                    value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone *</label>
                                <input type="tel" name="phone" class="form-control" required data-label="Phone">
                            </div>
                            <div class="form-group admin-form-full">
                                <label class="form-label">Address *</label>
                                <textarea name="address" class="form-control" rows="3" required data-label="Address"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control" required data-label="City">
                            </div>
                            <div class="form-group">
                                <label class="form-label">PIN Code *</label>
                                <input type="text" name="pincode" class="form-control" required data-label="PIN Code">
                            </div>
                        </div>
                    </div>

                    <div class="checkout-form-card" style="margin-top:var(--space-xl);">
                        <h3 style="margin-bottom:var(--space-xl); font-family:var(--font-main);">
                            <i class="fas fa-wallet"></i> Payment Method
                        </h3>
                        <label class="filter-option" style="padding:12px;background:var(--surface);border-radius:var(--radius-sm);margin-bottom:8px;">
                            <input type="radio" name="payment_method" value="cod" checked> Cash on Delivery
                        </label>
                        <label class="filter-option" style="padding:12px;background:var(--surface);border-radius:var(--radius-sm);margin-bottom:8px;">
                            <input type="radio" name="payment_method" value="upi"> UPI Payment
                        </label>
                        <label class="filter-option" style="padding:12px;background:var(--surface);border-radius:var(--radius-sm);margin-bottom:8px;">
                            <input type="radio" name="payment_method" value="card"> Credit/Debit Card
                        </label>
                        <label class="filter-option" style="padding:12px;background:var(--surface);border-radius:var(--radius-sm);margin-bottom:8px;">
                            <input type="radio" name="payment_method" value="netbanking"> Net Banking
                        </label>
                        <label class="filter-option" style="padding:12px;background:var(--surface);border-radius:var(--radius-sm);">
                            <input type="radio" name="payment_method" value="wallet"> Mobile Wallet
                        </label>

                        <!-- additional inputs for electronic payments -->
                        <div id="upiDetails" style="display:none;margin-top:var(--space-md);">
                            <label class="form-label">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control" placeholder="you@bank">
                        </div>
                        <div id="cardDetails" style="display:none;margin-top:var(--space-md);">
                            <div class="form-group">
                                <label class="form-label">Card Number</label>
                                <input type="text" name="card_number" class="form-control" placeholder="16 digit card" maxlength="19">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Expiry (MM/YY)</label>
                                <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV</label>
                                <input type="password" name="card_cvv" class="form-control" placeholder="3 or 4 digit" maxlength="4">
                            </div>
                        </div>

                        <div id="netbankingDetails" style="display:none;margin-top:var(--space-md);">
                            <div class="form-group">
                                <label class="form-label">Select Bank</label>
                                <select name="bank_name" class="form-control">
                                    <option value="">-- Select Bank --</option>
                                    <option value="sbi">State Bank of India</option>
                                    <option value="hdfc">HDFC Bank</option>
                                    <option value="icici">ICICI Bank</option>
                                    <option value="axis">Axis Bank</option>
                                    <option value="kotak">Kotak Mahindra Bank</option>
                                </select>
                            </div>
                        </div>

                        <div id="walletDetails" style="display:none;margin-top:var(--space-md);">
                            <div class="form-group">
                                <label class="form-label">Select Wallet</label>
                                <select name="wallet_name" class="form-control">
                                    <option value="">-- Select Wallet --</option>
                                    <option value="paytm">Paytm</option>
                                    <option value="phonepe">PhonePe</option>
                                    <option value="amazonpay">Amazon Pay</option>
                                    <option value="mobikwik">MobiKwik</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mobile Number Linked to Wallet</label>
                                <input type="tel" name="wallet_phone" class="form-control" placeholder="10 digit mobile number">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:var(--space-xl);">
                        <i class="fas fa-check-circle"></i> Place Order — <?php echo formatPrice($total); ?>
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <?php foreach ($cart as $item): ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);">
                    <div style="width:50px;height:65px;border-radius:4px;overflow:hidden;flex-shrink:0;background:var(--surface);">
                        <?php echo getBookCoverPlaceholder($item['title']); ?>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:0.9rem;"><?php echo htmlspecialchars(truncateText($item['title'], 40)); ?></div>
                        <div style="font-size:0.8rem;color:var(--text-muted);">Qty: <?php echo $item['quantity']; ?></div>
                    </div>
                    <div style="font-weight:600;color:var(--primary-light);"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                </div>
                <?php
endforeach; ?>

                <div class="summary-row" style="margin-top:var(--space-md);"><span>Subtotal</span><span><?php echo formatPrice($subtotal); ?></span></div>
                <div class="summary-row"><span>Shipping</span><span><?php echo formatPrice($shipping); ?></span></div>
                <div class="summary-row total"><span>Total</span><span><?php echo formatPrice($total); ?></span></div>
            </div>
        </div>
    </div>
</section>

<script>
// show/hide payment fields based on selected method
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        const upiSection = document.getElementById('upiDetails');
        const cardSection = document.getElementById('cardDetails');
        const netbankingSection = document.getElementById('netbankingDetails');
        const walletSection = document.getElementById('walletDetails');
        const radios = document.querySelectorAll('input[name="payment_method"]');
        
        function updateVisibility() {
            const sel = document.querySelector('input[name="payment_method"]:checked');
            if (!sel) return;
            upiSection.style.display = sel.value === 'upi' ? 'block' : 'none';
            cardSection.style.display = sel.value === 'card' ? 'block' : 'none';
            netbankingSection.style.display = sel.value === 'netbanking' ? 'block' : 'none';
            walletSection.style.display = sel.value === 'wallet' ? 'block' : 'none';
        }
        
        radios.forEach(r => r.addEventListener('change', updateVisibility));
        updateVisibility();
    });
})();
</script>
