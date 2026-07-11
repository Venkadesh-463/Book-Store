<?php
$pageTitle = 'My Wishlist';
$wishlistIds = getWishlist();
$books = [];
foreach ($wishlistIds as $id) {
    $b = getBookById($id);
    if ($b) $books[] = $b;
}
?>

<section class="section">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <h2><i class="fas fa-heart" style="color:var(--primary);"></i> My Wishlist</h2>
        </div>
        <?php if (empty($books)): ?>
            <div class="empty-state" style="padding:var(--space-xl);text-align:center;">
                <i class="fas fa-heart-broken"></i>
                <h3>No items in wishlist</h3>
                <p>Add books to your wishlist from the shop.</p>
                <a href="index.php?page=shop" class="btn btn-primary btn-sm"><i class="fas fa-store"></i> Browse Books</a>
            </div>
        <?php else: ?>
            <div class="book-grid">
                <?php foreach ($books as $book): ?>
                <div class="book-card wishlist-card" data-book-id="<?php echo $book['id']; ?>">
                    <div class="book-card-image">
                        <?php echo getBookCoverPlaceholder($book['title']); ?>
                    </div>
                    <div class="book-card-info">
                        <h3 class="book-card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="book-card-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <div class="book-card-footer" style="justify-content:space-between; align-items:center;">
                            <span class="book-card-price"><?php echo formatPrice($book['price']); ?></span>
                            <div style="display:flex;gap:12px;align-items:center;">
                                <button class="btn btn-link remove-wishlist" style="color:var(--danger);" title="Remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button class="btn btn-link" onclick="event.preventDefault(); addToCart(<?php echo $book['id']; ?>);" title="Add to Cart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// handle remove from wishlist
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.remove-wishlist').forEach(btn => {
        btn.addEventListener('click', function(){
            const card = this.closest('.wishlist-card');
            const id = card.dataset.bookId;
            fetch('<?php echo BASE_URL; ?>actions/wishlist.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: `action=remove&book_id=${id}&csrf_token=${encodeURIComponent(getCsrfToken())}`
            }).then(r=>r.json()).then(d=>{
                if(d.success){
                    card.remove();
                    updateWishlistBadge(d.count);
                    if(document.querySelectorAll('.wishlist-card').length===0){
                        location.reload();
                    }
                }
            });
        });
    });
});

function updateWishlistBadge(count){
    const badge = document.querySelector('.wishlist-link .cart-badge');
    if(badge){
        badge.textContent = count;
        badge.style.display = count>0 ? 'flex' : 'none';
    }
}
</script>