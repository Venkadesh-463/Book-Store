<?php
require_once __DIR__ . '/../config/config.php';
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
$pageTitle = 'Users';
$currentPage = 'users';
include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="admin-content">
        <div class="admin-page-header">
            <h1><i class="fas fa-users" style="color:var(--primary);"></i> Users</h1>
        </div>
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php
                            $users = getAllUsers();
                            foreach ($users as $user):
                                $joined = !empty($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A';
                                $isSelf = $user['id'] === $_SESSION['user_id'];
                            ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><span class="badge <?php echo $user['role'] === 'admin' ? 'badge-primary' : 'badge-success'; ?>"><?php echo htmlspecialchars(ucfirst($user['role'])); ?></span></td>
                                    <td><?php echo $joined; ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <?php if ($user['role'] !== 'admin' && !$isSelf): ?>
                                                <form method="POST" action="<?php echo BASE_URL; ?>actions/admin-actions.php" style="display:inline;" onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                                    <input type="hidden" name="action" value="delete_user">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="action-btn delete" title="Delete User"><i class="fas fa-trash"></i></button>
                                                </form>
                                            <?php elseif ($isSelf): ?>
                                                <span class="badge badge-secondary">Current Admin</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Protected</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
