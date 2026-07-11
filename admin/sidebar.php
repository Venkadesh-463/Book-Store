<?php
/** Admin Sidebar Partial */
?>
<aside class="admin-sidebar">
    <div class="admin-sidebar-header">
        <h3><i class="fas fa-shield-alt"></i> Admin Panel</h3>
    </div>
    <nav>
        <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="admin-nav-item <?php echo($currentPage ?? '') === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo BASE_URL; ?>admin/manage-books.php" class="admin-nav-item <?php echo($currentPage ?? '') === 'manage-books' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> Manage Books
        </a>
        <a href="<?php echo BASE_URL; ?>admin/add-book.php" class="admin-nav-item <?php echo($currentPage ?? '') === 'add-book' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i> Add Book
        </a>
        <a href="<?php echo BASE_URL; ?>admin/orders.php" class="admin-nav-item <?php echo($currentPage ?? '') === 'orders' ? 'active' : ''; ?>">
            <i class="fas fa-shopping-bag"></i> Orders
        </a>
        <a href="<?php echo BASE_URL; ?>admin/users.php" class="admin-nav-item <?php echo($currentPage ?? '') === 'users' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Users
        </a>
        <hr style="border:none;border-top:1px solid var(--border);margin:var(--space-lg) 0;">
        <a href="<?php echo BASE_URL; ?>" class="admin-nav-item">
            <i class="fas fa-home"></i> Back to Site
        </a>
    </nav>
</aside>
