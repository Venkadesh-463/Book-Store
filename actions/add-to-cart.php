<?php
/**
 * Add to Cart Action - BookHaven
 */
require_once __DIR__ . '/../config/config.php';

// Set JSON response header
header('Content-Type: application/json; charset=utf-8');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get input parameters
$bookId = (int)($_POST['book_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
$action = $_POST['action'] ?? 'add';
$token = $_POST['csrf_token'] ?? '';

// CSRF token validation
if (!verifyCsrfToken($token)) {
    error_log('add-to-cart: CSRF check failed. Token length: ' . strlen($token));
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

// Require login before adding to cart
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in to add items to your cart.']);
    exit;
}

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get book data
$book = getBookById($bookId);
if (!$book) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Book not found']);
    exit;
}

// Process action
switch ($action) {
    case 'remove':
        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($item) => $item['book_id'] != $bookId));
        break;

    case 'update':
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $bookId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
        unset($item);
        break;

    case 'add':
    default:
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['book_id'] == $bookId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        unset($item);
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'book_id' => $bookId,
                'title' => $book['title'],
                'author' => $book['author'],
                'price' => (float)$book['price'],
                'quantity' => $quantity,
                'cover_image' => $book['cover_image'] ?? ''
            ];
        }
        break;
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Success response
http_response_code(200);
echo json_encode([
    'success' => true,
    'cart_count' => getCartCount(),
    'subtotal' => round($subtotal, 2),
    'total' => round($subtotal + ($subtotal > 0 ? 49 : 0), 2),
    'message' => $action === 'remove' ? 'Item removed from cart' : 'Item added to cart'
]);
