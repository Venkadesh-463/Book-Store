<?php
require_once __DIR__ . '/../config/config.php';
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
$pageTitle = 'Add Book';
$currentPage = 'add-book';
$categories = getCategories();
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-plus-circle" style="color:var(--primary);"></i> Add New Book</h1>
            <a href="<?php echo BASE_URL; ?>admin/manage-books.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <div class="admin-form-card">
            <form method="POST" action="<?php echo BASE_URL; ?>actions/admin-actions.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <input type="hidden" name="action" value="add_book">
                <div class="admin-form-grid">
                    <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Author *</label><input type="text" name="author" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">ISBN</label><input type="text" name="isbn" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Category</label>
                        <select name="category_id" class="form-control">
                            <?php foreach ($categories as $c): ?><option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option><?php
endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Price (₹) *</label><input type="number" name="price" class="form-control" step="0.01" required></div>
                    <div class="form-group"><label class="form-label">Stock *</label><input type="number" name="stock" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Pages</label><input type="number" name="pages" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Publisher</label><input type="text" name="publisher" class="form-control"></div>
                    <div class="form-group admin-form-full"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
                    <div class="form-group admin-form-full"><label class="form-label">Cover Image</label><input type="file" name="cover_image" class="form-control" accept="image/*"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="margin-top:var(--space-lg);"><i class="fas fa-save"></i> Add Book</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
