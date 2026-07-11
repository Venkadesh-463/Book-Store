<?php
/**
 * Login Action - BookHaven
 */
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}

// CSRF protection
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlashMessage('error', 'Invalid request token.');
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}

$email = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    setFlashMessage('error', 'Please fill in all fields.');
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}

// Try database authentication
$pdo = getDBConnection();
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // regenerate session id to prevent fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            setFlashMessage('success', 'Welcome back, ' . $user['name'] . '!');

            $redirectTo = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '';
            unset($_SESSION['redirect_after_login']);

            if ($user['role'] === 'admin') {
                redirect(BASE_URL . 'admin/dashboard.php');
            }
            elseif ($redirectTo) {
                redirect(BASE_URL . 'index.php?page=' . $redirectTo);
            }
            else {
                redirect(BASE_URL);
            }
            exit;
        }
    }
    catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
    }
}

setFlashMessage('error', 'Invalid email or password.');
redirect(BASE_URL . 'index.php?page=login');
