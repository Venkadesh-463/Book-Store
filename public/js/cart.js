/**
 * Cart JavaScript - BookHaven
 * Global functions available on all pages
 */

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initCartActions();
});

// Helper: Get CSRF token from meta tag
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return (meta && meta.content) ? meta.content : '';
}

// Helper: Get base URL
function getBaseUrl() {
    const base = document.querySelector('meta[name="base-url"]');
    return base ? base.content : '/Online%20Book%20Store/';
}

// Wishlist helpers
function isInWishlist(bookId) {
    bookId = parseInt(bookId);
    return Array.isArray(window.wishlistIds) && window.wishlistIds.indexOf(bookId) !== -1;
}

function toggleWishlist(bookId) {
    const token = getCsrfToken();
    const url = getBaseUrl() + 'actions/wishlist.php';
    const adding = !isInWishlist(bookId);
    const body = new URLSearchParams({
        action: adding ? 'add' : 'remove',
        book_id: bookId,
        csrf_token: token
    });
    fetch(url, {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body.toString()})
        .then(r=>r.json())
        .then(d=>{
            if(d.success){
                // update icon state
                const btn = document.querySelector(`button.book-card-wishlist[onclick*="${bookId}"]`);
                if(btn){
                    const icon = btn.querySelector('i');
                    if(icon){
                        icon.classList.toggle('fas');
                        icon.classList.toggle('far');
                    }
                }
                // update global list
                if(adding) {
                    window.wishlistIds = window.wishlistIds || [];
                    window.wishlistIds.push(parseInt(bookId));
                } else {
                    window.wishlistIds = (window.wishlistIds || []).filter(id => id !== parseInt(bookId));
                }
                updateWishlistBadge(d.count);
            }
        });
}

function updateWishlistBadge(count){
    const badge = document.querySelector('.wishlist-link .cart-badge');
    if(badge){
        badge.textContent = count;
        badge.style.display = count>0?'flex':'none';
    }
}

// Main add to cart function
function addToCart(bookId) {
    const token = getCsrfToken();
    const url = getBaseUrl() + 'actions/add-to-cart.php';
    const body = new URLSearchParams({
        book_id: bookId,
        quantity: 1,
        csrf_token: token
    });
    
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            if (data.success) {
                showToast('✓ Added to cart!', 'success');
                updateCartBadge(data.cart_count);
            } else {
                showToast('✗ ' + (data.message || 'Error adding to cart'), 'error');
            }
        })
        .catch(err => {
            showToast('✗ Error: ' + err.message, 'error');
            console.error('addToCart error:', err);
        });
}
// Update cart item quantity
function updateCartItem(bookId, quantity) {
    const token = getCsrfToken();
    const url = getBaseUrl() + 'actions/add-to-cart.php';
    const body = new URLSearchParams({
        book_id: bookId,
        quantity: quantity,
        action: 'update',
        csrf_token: token
    });
    
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateCartTotals(data);
            } else {
                showToast('Error updating cart', 'error');
            }
        })
        .catch(err => showToast('Network error', 'error'));
}

// Remove item from cart
function removeFromCart(bookId) {
    const token = getCsrfToken();
    const url = getBaseUrl() + 'actions/add-to-cart.php';
    const body = new URLSearchParams({
        book_id: bookId,
        action: 'remove',
        csrf_token: token
    });
    
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.cart-item[data-book-id="${bookId}"]`);
                if (item) {
                    item.style.animation = 'slideOutRight 0.3s ease forwards';
                    setTimeout(() => {
                        item.remove();
                        updateCartTotals(data);
                        updateCartBadge(data.cart_count);
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            }
        })
        .catch(() => showToast('Network error', 'error'));
}

// Update cart badge count
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

// Update cart totals display
function updateCartTotals(data) {
    const subtotal = document.getElementById('cartSubtotal');
    const total = document.getElementById('cartTotal');
    if (subtotal && data.subtotal !== undefined) {
        subtotal.textContent = '₹' + parseFloat(data.subtotal).toFixed(2);
    }
    if (total && data.total !== undefined) {
        total.textContent = '₹' + parseFloat(data.total).toFixed(2);
    }
}

// Initialize cart page functionality
function initCartActions() {
    // Quantity update buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookId = this.dataset.bookId;
            const action = this.dataset.action;
            const input = this.closest('.quantity-control').querySelector('.qty-input');
            let qty = parseInt(input.value);

            if (action === 'increase') qty++;
            if (action === 'decrease' && qty > 1) qty--;

            input.value = qty;
            updateCartItem(bookId, qty);
        });
    });

    // Remove buttons
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookId = this.dataset.bookId;
            removeFromCart(bookId);
        });
    });
}
