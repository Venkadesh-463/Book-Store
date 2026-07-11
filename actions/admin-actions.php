<?php
/**
 * Centralized action handler for admin and other POST requests.
 * Note: this script is intentionally minimal for the demo; flesh out
 * specific cases as the application grows.
 */
require_once __DIR__ . '/../config/config.php';

// Accept both GET (for simple deletes) and POST for form submissions
$method = $_SERVER['REQUEST_METHOD'];

// CSRF validation for POST
if ($method === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        setFlashMessage('error', 'Invalid request token');
        redirect(BASE_URL);
        exit;
    }
}

$action = $_REQUEST['action'] ?? '';

if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('error', 'Access denied. Admin only.');
    redirect(BASE_URL);
    exit;
}

switch ($action) {
    case 'delete_user':
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        if ($userId <= 0) {
            setFlashMessage('error', 'Invalid user selected.');
            redirect(BASE_URL . 'admin/users.php');
            exit;
        }

        if ($userId === $_SESSION['user_id']) {
            setFlashMessage('error', 'You cannot delete your own admin account.');
            redirect(BASE_URL . 'admin/users.php');
            exit;
        }

        $user = getUserById($userId);
        if (!$user) {
            setFlashMessage('error', 'User not found.');
            redirect(BASE_URL . 'admin/users.php');
            exit;
        }

        if ($user['role'] === 'admin') {
            setFlashMessage('error', 'Admin accounts cannot be deleted here.');
            redirect(BASE_URL . 'admin/users.php');
            exit;
        }

        $pdo = getDBConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ?');
                $stmt->execute([$userId]);
                $orderCount = (int)$stmt->fetchColumn();

                if ($orderCount > 0) {
                    setFlashMessage('error', 'Cannot delete user with existing orders.');
                    redirect(BASE_URL . 'admin/users.php');
                    exit;
                }

                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                if ($stmt->execute([$userId])) {
                    setFlashMessage('success', 'User removed successfully.');
                } else {
                    setFlashMessage('error', 'Failed to delete the user.');
                }
            } catch (PDOException $e) {
                error_log('Delete user error: ' . $e->getMessage());
                setFlashMessage('error', 'An error occurred while deleting the user.');
            }
        } else {
            setFlashMessage('error', 'Database connection error.');
        }

        redirect(BASE_URL . 'admin/users.php');
        exit;

    case 'add_book':
        // implement book insertion logic (validate, sanitize, save file etc.)
        break;
    case 'edit_book':
        // implement update logic
        break;
    case 'update_profile':
        // update user profile data
        break;
    case 'contact':
        // handle contact form (perhaps send an email)
        break;
    default:
        redirect(BASE_URL);
        break;
}

