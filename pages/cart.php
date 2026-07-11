<?php
/**
 * Cart Page - BookHaven
 */
$pageTitle = 'Shopping Cart';
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = $subtotal > 0 ? 49.00 : 0;
$total = $subtotal + $shipping;
?>

<section class="cart-container">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2><i class="fas fa-shopping-cart" style="color:var(--primary);"></i> Shopping Cart</h2>
            <p><?php echo count($cart); ?> item(s) in your cart</p>
        </div>

        <?php if (empty($cart)): ?>
        <div class="empty-state">
            <i class="fas fa-shopping-bag"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any books yet. Start shopping!</p>
            <a href="index.php?page=shop" class="btn btn-primary btn-lg"><i class="fas fa-store"></i> Browse Shop</a>
        </div>
        <?php
else: ?>
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items">
                <?php foreach ($cart as $item): ?>
                <div class="cart-item" data-book-id="<?php echo $item['book_id']; ?>">
                    <div class="cart-item-image">
                        <?php echo getBookCoverPlaceholder($item['title']); ?>
                    </div>
                    <div>
                        <div class="cart-item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="cart-item-author"><?php echo htmlspecialchars($item['author']); ?></div>
                        <div class="cart-item-price"><?php echo formatPrice($item['price']); ?></div>
                    </div>
                    <div class="quantity-control">
                        <button type="button" class="qty-btn" data-book-id="<?php echo $item['book_id']; ?>" data-action="decrease">−</button>
                        <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" data-book-id="<?php echo $item['book_id']; ?>">
                        <button type="button" class="qty-btn" data-book-id="<?php echo $item['book_id']; ?>" data-action="increase">+</button>
                    </div>
                    <button class="cart-item-remove" data-book-id="<?php echo $item['book_id']; ?>" title="Remove">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <?php
    endforeach; ?>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row"><span>Subtotal</span><span id="cartSubtotal"><?php echo formatPrice($subtotal); ?></span></div>
                <div class="summary-row"><span>Shipping</span><span><?php echo formatPrice($shipping); ?></span></div>
                <div class="summary-row total"><span>Total</span><span id="cartTotal"><?php echo formatPrice($total); ?></span></div>

                <a href="index.php?page=checkout" class="btn btn-primary btn-block btn-lg" style="margin-top:var(--space-xl);">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </a>
                <a href="index.php?page=shop" class="btn btn-secondary btn-block" style="margin-top:var(--space-sm);">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
        <?php
endif; ?>
    </div>
</section>
