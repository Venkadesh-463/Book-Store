<?php
require_once __DIR__ . '/../config/config.php';
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
$pageTitle = 'Manage Books';
$currentPage = 'manage-books';
$books = getBooks();
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-book" style="color:var(--primary);"></i> Manage Books</h1>
            <a href="<?php echo BASE_URL; ?>admin/add-book.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Book</a>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Price</th><th>Stock</th><th>Rating</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo $book['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo formatPrice($book['price']); ?></td>
                                <td><span class="badge <?php echo $book['stock'] > 10 ? 'badge-success' : 'badge-warning'; ?>"><?php echo $book['stock']; ?></span></td>
                                <td><?php echo $book['rating']; ?> ★</td>
                                <td>
                                    <div class="action-btns">
                                        <a href="<?php echo BASE_URL; ?>admin/edit-book.php?id=<?php echo $book['id']; ?>" class="action-btn" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="action-btn delete" title="Delete" onclick="if(confirmDelete())window.location='<?php echo BASE_URL; ?>actions/admin-actions.php?action=delete_book&id=<?php echo $book['id']; ?>'"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
