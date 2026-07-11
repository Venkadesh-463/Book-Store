<?php
/**
 * Wishlist action handler
 */
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

// CSRF
$token = $_POST['csrf_token'] ?? '';
if (!verifyCsrfToken($token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF error']);
    exit;
}

$action = $_POST['action'] ?? '';
$bookId = (int)($_POST['book_id'] ?? 0);

switch ($action) {
    case 'add':
        if ($bookId) {
            addToWishlist($bookId);
        }
        break;
    case 'remove':
        if ($bookId) {
            removeFromWishlist($bookId);
        }
        break;
}

$count = getWishlistCount();

echo json_encode(['success' => true, 'count' => $count]);
