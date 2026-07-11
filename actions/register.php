<?php
/**
 * Register Action - BookHaven
 */
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

// CSRF protection
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlashMessage('error', 'Invalid request token.');
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validation
if (empty($name) || empty($email) || empty($password)) {
    setFlashMessage('error', 'Please fill in all required fields.');
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

if (strlen($password) < 6) {
    setFlashMessage('error', 'Password must be at least 6 characters.');
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

if ($password !== $confirmPassword) {
    setFlashMessage('error', 'Passwords do not match.');
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlashMessage('error', 'Please enter a valid email address.');
    redirect(BASE_URL . 'index.php?page=register');
    exit;
}

$pdo = getDBConnection();
if ($pdo) {
    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            setFlashMessage('error', 'An account with this email already exists.');
            redirect(BASE_URL . 'index.php?page=register');
            exit;
        }

        // Insert user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
        $stmt->execute([$name, $email, $hashedPassword]);

        // Auto-login (and regenerate session id)
        $userId = $pdo->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'user';

        setFlashMessage('success', 'Account created successfully! Welcome, ' . $name . '!');
        redirect(BASE_URL);
        exit;
    }
    catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        setFlashMessage('error', 'Registration failed. Please try again.');
        redirect(BASE_URL . 'index.php?page=register');
        exit;
    }
}

// Demo mode (no DB)
$_SESSION['user_id'] = rand(10, 999);
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = 'user';
setFlashMessage('success', 'Account created (demo mode)! Welcome, ' . $name . '!');
redirect(BASE_URL);
