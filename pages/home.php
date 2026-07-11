<?php
/**
 * Home Page - BookHaven
 */
$pageTitle = 'Home';
$featuredBooks = getBooks(8);
$categories = getCategories();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-sparkles"></i> Welcome to BookHaven
            </div>
            <h1>Discover Your Next Favourite Book</h1>
            <p>Explore our curated collection of bestsellers, classics, and hidden gems. From fiction to self-help, find the perfect read for every mood.</p>
            <div class="hero-buttons">
                <a href="index.php?page=shop" class="btn btn-primary btn-lg">
                    <i class="fas fa-store"></i> Browse Shop
                </a>
                <a href="#featured" class="btn btn-outline btn-lg">
                    <i class="fas fa-fire"></i> Featured Books
                </a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-value">10K+</div>
                    <div class="hero-stat-label">Books Available</div>
                </div>
                <div>
                    <div class="hero-stat-value">5K+</div>
                    <div class="hero-stat-label">Happy Readers</div>
                </div>
                <div>
                    <div class="hero-stat-value">500+</div>
                    <div class="hero-stat-label">Authors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-visual">
        <div class="floating-book"></div>
        <div class="floating-book"></div>
        <div class="floating-book"></div>
    </div>
</section>

<!-- Categories Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Browse by Genre</span>
            <h2>Popular Categories</h2>
            <p>Find your favourite genre and discover amazing books</p>
        </div>
        <div class="category-grid">
            <?php
$icons = ['📚', '🔬', '💼', '🧠', '🔍', '❤️', '👶', '📖'];
$faIcons = ['fa-book', 'fa-flask', 'fa-briefcase', 'fa-brain', 'fa-search', 'fa-heart', 'fa-child', 'fa-bookmark'];
foreach ($categories as $i => $cat): ?>
            <a href="index.php?page=shop&category=<?php echo $cat['id']; ?>" class="category-card">
                <i class="fas <?php echo $faIcons[$i % count($faIcons)]; ?>"></i>
                <h4><?php echo htmlspecialchars($cat['name']); ?></h4>
            </a>
            <?php
endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<section class="section section-alt" id="featured">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Handpicked for You</span>
            <h2>Featured Books</h2>
            <p>Our editors' top picks this month</p>
        </div>
        <div class="book-grid">
            <?php foreach (array_slice($featuredBooks, 0, 4) as $book): ?>
            <a href="index.php?page=book-details&id=<?php echo $book['id']; ?>" class="book-card">
                <div class="book-card-image">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?php echo BASE_URL . 'uploads/book-covers/' . $book['cover_image']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <?php
    else: ?>
                        <?php echo getBookCoverPlaceholder($book['title']); ?>
                    <?php
    endif; ?>
                    <span class="book-card-badge">Featured</span>
                    <button class="book-card-wishlist" onclick="event.preventDefault(); toggleWishlist(<?php echo $book['id']; ?>); this.querySelector('i').classList.toggle('far'); this.querySelector('i').classList.toggle('fas');">
                        <i class="<?php echo isInWishlist($book['id']) ? 'fas' : 'far'; ?> fa-heart"></i>
                    </button>
                </div>
                <div class="book-card-info">
                    <span class="book-card-category"><?php
    $cats = getCategories();
    foreach ($cats as $c)
        if ($c['id'] == $book['category_id'])
            echo $c['name'];
?></span>
                    <h3 class="book-card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="book-card-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                    <?php echo getStarRating($book['rating']); ?>
                    <div class="book-card-footer">
                        <span class="book-card-price"><?php echo formatPrice($book['price']); ?></span>
                        <button class="btn-add-cart" onclick="event.preventDefault(); addToCart(<?php echo $book['id']; ?>);" title="Add to Cart">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </a>
            <?php
endforeach; ?>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Just Arrived</span>
            <h2>New Arrivals</h2>
            <p>The latest additions to our growing library</p>
        </div>
        <div class="book-grid">
            <?php foreach (array_slice($featuredBooks, 4, 4) as $book): ?>
            <a href="index.php?page=book-details&id=<?php echo $book['id']; ?>" class="book-card">
                <div class="book-card-image">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?php echo BASE_URL . 'uploads/book-covers/' . $book['cover_image']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <?php
    else: ?>
                        <?php echo getBookCoverPlaceholder($book['title']); ?>
                    <?php
    endif; ?>
                    <span class="book-card-badge" style="background: var(--accent);">New</span>
                </div>
                <div class="book-card-info">
                    <span class="book-card-category"><?php
    foreach ($cats as $c)
        if ($c['id'] == $book['category_id'])
            echo $c['name'];
?></span>
                    <h3 class="book-card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="book-card-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                    <?php echo getStarRating($book['rating']); ?>
                    <div class="book-card-footer">
                        <span class="book-card-price"><?php echo formatPrice($book['price']); ?></span>
                        <button class="btn-add-cart" onclick="event.preventDefault(); addToCart(<?php echo $book['id']; ?>);">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </a>
            <?php
endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: var(--space-2xl);">
            <a href="index.php?page=shop" class="btn btn-outline btn-lg"><i class="fas fa-arrow-right"></i> View All Books</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-label">What Readers Say</span>
            <h2>Customer Reviews</h2>
        </div>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="testimonial-text">BookHaven has completely transformed my reading habits. The curated collections always help me find my next great read!</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AP</div>
                    <div>
                        <div class="testimonial-name">Arjun Patel</div>
                        <div class="testimonial-role">Avid Reader</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">Fast delivery, amazing prices, and such a wide selection. I've recommended BookHaven to all my friends and family.</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SK</div>
                    <div>
                        <div class="testimonial-name">Sneha Kumar</div>
                        <div class="testimonial-role">Book Club Organizer</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-text">The website is beautifully designed and so easy to navigate. Finding books by genre has never been simpler!</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">RV</div>
                    <div>
                        <div class="testimonial-name">Rahul Verma</div>
                        <div class="testimonial-role">Software Developer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
