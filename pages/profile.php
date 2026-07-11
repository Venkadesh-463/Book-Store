<?php
$pageTitle = 'My Profile';
$user = isLoggedIn() ? ['name' => $_SESSION['user_name'] ?? 'User', 'email' => $_SESSION['user_email'] ?? ''] : null;
if (!$user) {
    redirect('index.php?page=login');
    exit;
}
$initials = strtoupper(substr($user['name'], 0, 1));
?>

<section class="section">
    <div class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar"><?php echo $initials; ?></div>
                <h3 style="margin-bottom:4px;"><?php echo htmlspecialchars($user['name']); ?></h3>
                <p style="font-size:0.9rem;margin-bottom:var(--space-xl);"><?php echo htmlspecialchars($user['email']); ?></p>
                <a href="<?php echo BASE_URL; ?>actions/logout.php" class="btn btn-danger btn-block btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <!-- Content -->
            <div>
                <div class="checkout-form-card" style="margin-bottom:var(--space-xl);">
                    <h3 style="font-family:var(--font-main);margin-bottom:var(--space-xl);">
                        <i class="fas fa-user-edit" style="color:var(--primary);"></i> Edit Profile
                    </h3>
                    <form method="POST" action="<?php echo BASE_URL; ?>actions/admin-actions.php">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="admin-form-grid">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" placeholder="Your phone number">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Your address">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    </form>
                </div>

                <!-- Order History -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h3><i class="fas fa-box" style="color:var(--accent);margin-right:8px;"></i> Order History</h3>
                    </div>
                    <div class="admin-card-body">
                        <?php
                        $orders = getOrdersByUser($_SESSION['user_id']);
                        if (empty($orders)) : ?>
                        <div class="empty-state" style="padding:var(--space-xl);">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No orders yet</h3>
                            <p>When you place orders, they'll appear here.</p>
                            <a href="index.php?page=shop" class="btn btn-primary btn-sm"><i class="fas fa-store"></i> Start Shopping</a>
                        </div>
                        <?php else: ?>
                        <table class="admin-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Txn ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td><?php echo $o['id']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($o['created_at'])); ?></td>
                                    <td><?php echo formatPrice($o['total_amount']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($o['payment_method'])); ?></td>
                                    <td><?php echo formatPaymentStatus($o['payment_status'] ?? 'pending'); ?></td>
                                    <td><?php echo htmlspecialchars($o['transaction_id'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
