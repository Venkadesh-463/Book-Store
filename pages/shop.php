<?php
/**
 * Shop Page - BookHaven
 */
$pageTitle = 'Shop';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? (int) $_GET['category'] : null;

$limit = 12;
$pageNum = isset($_GET['page_num']) ? (int) $_GET['page_num'] : 1;
if ($pageNum < 1)
    $pageNum = 1;
$offset = ($pageNum - 1) * $limit;

$books = getBooksPaginated($limit, $offset, $categoryFilter, $search);
$totalBooks = getTotalBooksCount($categoryFilter, $search);
$totalPages = ceil($totalBooks / $limit);

$categories = getCategories();
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Collection</span>
            <h2><?php echo $search ? 'Search Results for "' . htmlspecialchars($search) . '"' : 'All Books'; ?></h2>
            <p><?php echo $totalBooks; ?> books found</p>
        </div>

        <!-- Mobile Filter Toggle -->
        <button class="btn btn-secondary btn-sm filter-toggle" onclick="toggleFilter()" style="display:none;">
            <i class="fas fa-filter"></i> Filters
        </button>

        <div class="shop-layout">
            <!-- Filter Sidebar -->
            <aside class="filter-sidebar">
                <h3 class="filter-title"><i class="fas fa-sliders-h"></i> Filters</h3>

                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="shop">

                    <div class="filter-group">
                        <h4>Category</h4>
                        <label class="filter-option">
                            <input type="radio" name="category" value="" <?php echo !$categoryFilter ? 'checked' : ''; ?> onchange="this.form.submit()">
                            All Categories
                        </label>
                        <?php foreach ($categories as $cat): ?>
                            <label class="filter-option">
                                <input type="radio" name="category" value="<?php echo $cat['id']; ?>" <?php echo $categoryFilter == $cat['id'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </label>
                            <?php
                        endforeach; ?>
                    </div>

                    <div class="filter-group">
                        <h4>Price Range</h4>
                        <label class="filter-option"><input type="checkbox"> Under ₹200</label>
                        <label class="filter-option"><input type="checkbox"> ₹200 - ₹400</label>
                        <label class="filter-option"><input type="checkbox"> ₹400 - ₹600</label>
                        <label class="filter-option"><input type="checkbox"> Above ₹600</label>
                    </div>

                    <div class="filter-group">
                        <h4>Rating</h4>
                        <label class="filter-option"><input type="checkbox"> 4★ & above</label>
                        <label class="filter-option"><input type="checkbox"> 3★ & above</label>
                    </div>
                </form>
            </aside>

            <!-- Books Grid -->
            <div>
                <?php if (empty($books)): ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No books found</h3>
                        <p>Try adjusting your filters or search terms.</p>
                        <a href="index.php?page=shop" class="btn btn-primary"><i class="fas fa-redo"></i> Clear Filters</a>
                    </div>
                    <?php
                else: ?>
                    <div class="book-grid">
                        <?php foreach ($books as $book): ?>
                            <a href="index.php?page=book-details&id=<?php echo $book['id']; ?>" class="book-card">
                                <div class="book-card-image">
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <img src="<?php echo BASE_URL . 'uploads/book-covers/' . $book['cover_image']; ?>"
                                            alt="<?php echo htmlspecialchars($book['title']); ?>">
                                        <?php
                                    else: ?>
                                        <?php echo getBookCoverPlaceholder($book['title']); ?>
                                        <?php
                                    endif; ?>
                                    <button class="book-card-wishlist"
                                        onclick="event.preventDefault(); toggleWishlist(<?php echo $book['id']; ?>); this.querySelector('i').classList.toggle('far'); this.querySelector('i').classList.toggle('fas');">
                                        <i class="<?php echo isInWishlist($book['id']) ? 'fas' : 'far'; ?> fa-heart"></i>
                                    </button>
                                </div>
                                <div class="book-card-info">
                                    <span class="book-card-category">
                                        <?php
                                        foreach ($categories as $c)
                                            if ($c['id'] == $book['category_id'])
                                                echo $c['name'];
                                        ?>
                                    </span>
                                    <h3 class="book-card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                                    <p class="book-card-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                                    <?php echo getStarRating($book['rating']); ?>
                                    <div class="book-card-footer">
                                        <span class="book-card-price"><?php echo formatPrice($book['price']); ?></span>
                                        <button class="btn-add-cart"
                                            onclick="event.preventDefault(); addToCart(<?php echo $book['id']; ?>);">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <div class="pagination" style="margin-top: var(--space-xl);">
                            <?php
                            $queryParams = $_GET;
                            $pageNumbers = [];
                            if ($totalPages <= 3) {
                                $pageNumbers = range(1, $totalPages);
                            } elseif ($pageNum === 1) {
                                $pageNumbers = [1, 2, 3];
                            } elseif ($pageNum === $totalPages) {
                                $pageNumbers = [$totalPages - 2, $totalPages - 1, $totalPages];
                            } else {
                                $pageNumbers = [$pageNum - 1, $pageNum, $pageNum + 1];
                            }
                            ?>

                            <?php if ($pageNum > 1): ?>
                                <?php $queryParams['page_num'] = $pageNum - 1; ?>
                                <a href="index.php?<?php echo http_build_query($queryParams); ?>" class="page-link arrow" title="Previous page">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php foreach ($pageNumbers as $page):
                                if ($page < 1 || $page > $totalPages) {
                                    continue;
                                }
                                $queryParams['page_num'] = $page;
                                ?>
                                <a href="index.php?<?php echo http_build_query($queryParams); ?>" class="page-link <?php echo $page === $pageNum ? 'active' : ''; ?>">
                                    <?php echo $page; ?>
                                </a>
                            <?php endforeach; ?>

                            <?php if ($pageNum < $totalPages): ?>
                                <?php $queryParams['page_num'] = $pageNum + 1; ?>
                                <a href="index.php?<?php echo http_build_query($queryParams); ?>" class="page-link arrow" title="Next page">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</section>