<?php
require_once __DIR__ . '/../config/config.php';
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$book = getBookById($bookId);
if (!$book) {
    setFlashMessage('error', 'Book not found.');
    redirect(BASE_URL . 'admin/manage-books.php');
    exit;
}
$pageTitle = 'Edit Book';
$currentPage = 'manage-books';
$categories = getCategories();
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-edit" style="color:var(--primary);"></i> Edit Book</h1>
            <a href="<?php echo BASE_URL; ?>admin/manage-books.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="admin-form-card">
            <form method="POST" action="<?php echo BASE_URL; ?>actions/admin-actions.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <input type="hidden" name="action" value="edit_book">
                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                <div class="admin-form-grid">
                    <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required></div>
                    <div class="form-group"><label class="form-label">Author *</label><input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required></div>
                    <div class="form-group"><label class="form-label">ISBN</label><input type="text" name="isbn" class="form-control" value="<?php echo $book['isbn']; ?>"></div>
                    <div class="form-group"><label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <?php foreach ($categories as $c): ?><option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $book['category_id'] ? 'selected' : ''; ?>><?php echo $c['name']; ?></option><?php
endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Price (₹) *</label><input type="number" name="price" class="form-control" step="0.01" value="<?php echo $book['price']; ?>" required></div>
                    <div class="form-group"><label class="form-label">Stock *</label><input type="number" name="stock" class="form-control" value="<?php echo $book['stock']; ?>" required></div>
                    <div class="form-group"><label class="form-label">Pages</label><input type="number" name="pages" class="form-control" value="<?php echo $book['pages']; ?>"></div>
                    <div class="form-group"><label class="form-label">Publisher</label><input type="text" name="publisher" class="form-control" value="<?php echo htmlspecialchars($book['publisher'] ?? ''); ?>"></div>
                    <div class="form-group admin-form-full"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea></div>
                    <div class="form-group admin-form-full"><label class="form-label">Cover Image</label><input type="file" name="cover_image" class="form-control" accept="image/*"><small style="color:var(--text-muted);">Leave empty to keep current image</small></div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="margin-top:var(--space-lg);"><i class="fas fa-save"></i> Update Book</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
