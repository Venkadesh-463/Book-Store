<?php
/**
 * Admin Dashboard - BookHaven
 */
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('error', 'Access denied. Admin only.');
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}

$pageTitle = 'Admin Dashboard';
$currentPage = 'dashboard';
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-tachometer-alt" style="color:var(--primary);"></i> Dashboard</h1>
            <span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Live</span>
        </div>

        <!-- Stats -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Revenue</h4>
                    <div class="stat-value">₹45,200</div>
                    <div class="stat-change positive"><i class="fas fa-arrow-up"></i> 12.5% this month</div>
                </div>
                <div class="stat-icon purple"><i class="fas fa-rupee-sign"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Orders</h4>
                    <div class="stat-value">128</div>
                    <div class="stat-change positive"><i class="fas fa-arrow-up"></i> 8.3% this month</div>
                </div>
                <div class="stat-icon green"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Total Users</h4>
                    <div class="stat-value">542</div>
                    <div class="stat-change positive"><i class="fas fa-arrow-up"></i> 15.2%</div>
                </div>
                <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Books Listed</h4>
                    <div class="stat-value"><?php echo count(getBooks()); ?></div>
                    <div class="stat-change positive"><i class="fas fa-arrow-up"></i> 3 new</div>
                </div>
                <div class="stat-icon pink"><i class="fas fa-book"></i></div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Recent Orders</h3>
                <a href="<?php echo BASE_URL; ?>admin/orders.php" class="btn btn-sm btn-secondary">View All</a>
            </div>
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Order ID</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>#ORD-001</td><td>Arjun Patel</td><td>₹749.00</td><td><span class="badge badge-success">Delivered</span></td><td>Feb 20, 2026</td></tr>
                            <tr><td>#ORD-002</td><td>Sneha Kumar</td><td>₹1,298.00</td><td><span class="badge badge-warning">Shipped</span></td><td>Feb 21, 2026</td></tr>
                            <tr><td>#ORD-003</td><td>Rahul Verma</td><td>₹599.00</td><td><span class="badge badge-primary">Processing</span></td><td>Feb 22, 2026</td></tr>
                            <tr><td>#ORD-004</td><td>Priya Singh</td><td>₹875.00</td><td><span class="badge badge-danger">Cancelled</span></td><td>Feb 23, 2026</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
