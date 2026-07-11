<?php
require_once __DIR__ . '/../config/config.php';
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
$pageTitle = 'Orders';
$currentPage = 'orders';
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-shopping-bag" style="color:var(--primary);"></i> Orders</h1>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
                        <tbody>
                        <?php
                        $orders = getAllOrders();
                        if (!empty($orders)) {
                            foreach ($orders as $o):
                                $itemsCount = 0;
                                // count items for this order
                                $stmt = getDBConnection()->prepare('SELECT SUM(quantity) FROM order_items WHERE order_id = ?');
                                $stmt->execute([$o['id']]);
                                $itemsCount = $stmt->fetchColumn();
                        ?>
                        <tr>
                            <td>#<?php echo $o['id']; ?></td>
                            <td><?php echo htmlspecialchars($o['customer_name'] ?? ''); ?></td>
                            <td><?php echo $itemsCount; ?></td>
                            <td><?php echo formatPrice($o['total_amount']); ?></td>
                            <td><?php echo strtoupper(htmlspecialchars($o['payment_method'])); ?></td>
                            <td><?php echo formatPaymentStatus($o['payment_status'] ?? 'pending'); ?></td>
                            <td><?php echo date('M d', strtotime($o['created_at'])); ?></td>
                            <td><div class="action-btns"><button class="action-btn"><i class="fas fa-eye"></i></button></div></td>
                        </tr>
                        <?php
                            endforeach;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
