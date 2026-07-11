<?php
/**
 * Application Configuration
 * Online Book Store
 */

// require PHP 8.0+ for typed properties, attributes, and other modern features
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('This application requires PHP 8.0 or higher. You are running ' . PHP_VERSION);
}

// secure session configuration prior to start
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
// only send secure cookies when HTTPS is used
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (disable display in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Site constants
define('SITE_NAME', 'BookHaven');
define('SITE_TAGLINE', 'Your Gateway to Knowledge');
define('BASE_URL', '/Online%20Book%20Store/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/book-covers/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// payment gateway settings (replace with your own keys)
define('PAYMENT_GATEWAY', 'test'); // e.g. 'stripe', 'razorpay'
define('STRIPE_SECRET_KEY', 'sk_test_XXXXXXXXXXXXXXXX');
define('RAZORPAY_KEY_ID', '');
define('RAZORPAY_KEY_SECRET', '');

// Include database connection
require_once __DIR__ . '/database.php';

// Include helper functions
require_once __DIR__ . '/../includes/functions.php';

// Payment gateway helpers (stubbed for demo)
require_once __DIR__ . '/../includes/payment.php';
