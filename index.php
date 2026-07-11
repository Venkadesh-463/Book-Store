<?php
/**
 * Front Controller
 * Online Book Store
 */

require_once __DIR__ . '/config/config.php';

// Get the requested page
$page = isset($_GET['page']) ? sanitize($_GET['page']) : 'home';

// Define valid pages
$validPages = [
    'home', 'shop', 'book-details', 'cart', 'checkout', 'wishlist',
    'login', 'register', 'profile', 'contact'
];

// Check if it's a valid page
if (!in_array($page, $validPages)) {
    $page = 'home';
}

// Pages that require authentication
$authPages = ['cart', 'checkout', 'profile', 'wishlist'];

if (in_array($page, $authPages) && !isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $page;
    redirect('index.php?page=login');
    exit;
}

// Include header
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

// Include the requested page
$pageFile = __DIR__ . '/pages/' . $page . '.php';
if (file_exists($pageFile)) {
    include $pageFile;
}
else {
    echo '<div class="container" style="padding: 100px 0; text-align: center;">';
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>The page you are looking for does not exist.</p>';
    echo '<a href="index.php" class="btn btn-primary">Go Home</a>';
    echo '</div>';
}

// Include footer
include __DIR__ . '/includes/footer.php';
