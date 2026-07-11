<?php
/**
 * Book Details Page - BookHaven
 */
$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = getBookById($bookId);

if (!$book) {
    echo '<div class="container empty-state" style="padding: 100px 0;">';
    echo '<i class="fas fa-book"></i><h3>Book Not Found</h3>';
    echo '<p>The book you\'re looking for doesn\'t exist.</p>';
    echo '<a href="index.php?page=shop" class="btn btn-primary">Back to Shop</a></div>';
    return;
}

$pageTitle = $book['title'];
$categories = getCategories();
$catName = '';
foreach ($categories as $c)
    if ($c['id'] == $book['category_id'])
        $catName = $c['name'];
$relatedBooks = array_filter(getBooks(4), fn($b) => $b['id'] != $book['id']);
?>

<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <div style="margin-bottom: var(--space-xl); font-size: 0.9rem; color: var(--text-muted);">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right" style="font-size:0.7rem;margin:0 8px;"></i>
            <a href="index.php?page=shop">Shop</a> <i class="fas fa-chevron-right" style="font-size:0.7rem;margin:0 8px;"></i>
            <span style="color: var(--text-primary);"><?php echo htmlspecialchars($book['title']); ?></span>
        </div>

        <div class="book-detail-layout">
            <!-- Cover Image -->
            <div class="book-detail-cover">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?php echo BASE_URL . 'uploads/book-covers/' . $book['cover_image']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php
else: ?>
                    <?php echo getBookCoverPlaceholder($book['title']); ?>
                <?php
endif; ?>
            </div>

            <!-- Details -->
            <div class="book-detail-info">
                <span class="badge badge-primary" style="margin-bottom: var(--space-md);"><?php echo $catName; ?></span>
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="book-detail-author">by <strong><?php echo htmlspecialchars($book['author']); ?></strong></p>
                <?php echo getStarRating($book['rating']); ?>
                <div class="book-detail-price"><?php echo formatPrice($book['price']); ?></div>

                <p style="line-height:1.8;"><?php echo htmlspecialchars($book['description']); ?></p>

                <!-- Meta Info -->
                <div class="book-detail-meta">
                    <div class="meta-item"><div class="meta-label">ISBN</div><div class="meta-value"><?php echo $book['isbn']; ?></div></div>
                    <div class="meta-item"><div class="meta-label">Pages</div><div class="meta-value"><?php echo $book['pages']; ?></div></div>
                    <div class="meta-item"><div class="meta-label">Publisher</div><div class="meta-value"><?php echo htmlspecialchars($book['publisher'] ?? 'N/A'); ?></div></div>
                    <div class="meta-item"><div class="meta-label">Stock</div><div class="meta-value"><?php echo $book['stock'] > 0 ? '<span style="color:var(--success)">In Stock (' . $book['stock'] . ')</span>' : '<span style="color:var(--danger)">Out of Stock</span>'; ?></div></div>
                </div>

                <!-- Actions -->
                <div class="book-detail-actions">
                    <div class="quantity-control">
                        <button type="button" class="qty-btn" onclick="updateQuantity(<?php echo $book['id']; ?>, -1)">−</button>
                        <input type="number" class="qty-input" value="1" min="1" max="<?php echo $book['stock']; ?>" data-book-id="<?php echo $book['id']; ?>">
                        <button type="button" class="qty-btn" onclick="updateQuantity(<?php echo $book['id']; ?>, 1)">+</button>
                    </div>
                    <button class="btn btn-primary btn-lg" onclick="addToCart(<?php echo $book['id']; ?>)">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button id="wishlistBtn" class="btn btn-secondary btn-lg" onclick="toggleWishlist(<?php echo $book['id']; ?>)">
                        <i id="wishlistIcon" class="<?php echo isInWishlist($book['id']) ? 'fas' : 'far'; ?> fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Books -->
        <?php if (!empty($relatedBooks)): ?>
        <div style="margin-top: var(--space-3xl);">
            <div class="section-header"><h2>You May Also Like</h2></div>
            <div class="book-grid">
                <?php foreach (array_slice($relatedBooks, 0, 4) as $rb): ?>
                <a href="index.php?page=book-details&id=<?php echo $rb['id']; ?>" class="book-card">
                    <div class="book-card-image">
                        <?php echo getBookCoverPlaceholder($rb['title']); ?>
                    </div>
                    <div class="book-card-info">
                        <h3 class="book-card-title"><?php echo htmlspecialchars($rb['title']); ?></h3>
                        <p class="book-card-author">by <?php echo htmlspecialchars($rb['author']); ?></p>
                        <div class="book-card-footer">
                            <span class="book-card-price"><?php echo formatPrice($rb['price']); ?></span>
                        </div>
                    </div>
                </a>
                <?php
    endforeach; ?>
            </div>
        </div>
        <?php
endif; ?>
    </div>
</section>
