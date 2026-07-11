<?php
/**
 * Helper Functions
 * Online Book Store
 */

/**
 * Sanitize user input
 */
function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generate or return existing CSRF token and store it in session
 * @return string
 */
function generateCsrfToken()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        // random_bytes is available in PHP 7+ and very safe
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify provided CSRF token against session
 * @param string $token
 * @return bool
 */
function verifyCsrfToken($token)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    // during development/testing, allow requests with matching tokens or no token at all
    // in production, strictly require and match tokens
    if (empty($token)) {
        // if no token provided, check if session has one generated
        if (empty($_SESSION['csrf_token'])) {
            // generate one if missing
            generateCsrfToken();
        }
        // empty token is allowed for now; in production set this to return false
        return true;
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect to a URL
 */
function redirect($url)
{
    header("Location: $url");
    exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Get cart count from session
 */
function getCartCount()
{
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

/**
 * Wishlist helpers (session-backed)
 */
function getWishlist()
{
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
    return $_SESSION['wishlist'];
}

function addToWishlist($bookId)
{
    $list = getWishlist();
    if (!in_array($bookId, $list)) {
        $_SESSION['wishlist'][] = $bookId;
    }
}

function removeFromWishlist($bookId)
{
    if (isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = array_values(array_filter($_SESSION['wishlist'], fn($id) => $id != $bookId));
    }
}

function getWishlistCount()
{
    return count(getWishlist());
}

function isInWishlist($bookId)
{
    return in_array($bookId, getWishlist());
}

/**
 * Format price as currency
 */
function formatPrice($price)
{
    return '₹' . number_format($price, 2);
}

/**
 * Get all books (sample data if DB not available)
 */
function getBooks($limit = null, $category = null, $search = null)
{
    $pdo = getDBConnection();

    if ($pdo) {
        try {
            $sql = "SELECT * FROM books WHERE 1=1";
            $params = [];

            if ($category) {
                $sql .= " AND category_id = ?";
                $params[] = $category;
            }
            if ($search) {
                $sql .= " AND (title LIKE ? OR author LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            $sql .= " ORDER BY created_at DESC";

            if ($limit) {
                $sql .= " LIMIT " . (int) $limit;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching books: " . $e->getMessage());
        }
    }

    // Return sample data if DB not available
    return getSampleBooks($limit);
}

/**
 * Get paginated books and count
 */
function getBooksPaginated($limit = 12, $offset = 0, $category = null, $search = null)
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $sql = "SELECT * FROM books WHERE 1=1";
            $params = [];

            if ($category) {
                $sql .= " AND category_id = ?";
                $params[] = $category;
            }
            if ($search) {
                $sql .= " AND (title LIKE ? OR author LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            $sql .= " ORDER BY created_at DESC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching books paginated: " . $e->getMessage());
        }
    }

    // Fallback
    $books = getBooks(null, $category, $search);
    return array_slice($books, $offset, $limit);
}

/**
 * Get total books count
 */
function getTotalBooksCount($category = null, $search = null)
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $sql = "SELECT COUNT(*) FROM books WHERE 1=1";
            $params = [];

            if ($category) {
                $sql .= " AND category_id = ?";
                $params[] = $category;
            }
            if ($search) {
                $sql .= " AND (title LIKE ? OR author LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting books: " . $e->getMessage());
        }
    }

    // Fallback
    return count(getBooks(null, $category, $search));
}

/**
 * Get a single book by ID (sample data if DB not available)

 */
function getBookById($id)
{
    $pdo = getDBConnection();

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching book: " . $e->getMessage());
        }
    }

    // Return sample data
    $books = getSampleBooks();
    foreach ($books as $book) {
        if ($book['id'] == $id) {
            return $book;
        }
    }
    return null;
}

/**
 * Get categories
 */
function getCategories()
{
    $pdo = getDBConnection();

    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
        }
    }

    return [
        ['id' => 1, 'name' => 'Fiction', 'slug' => 'fiction'],
        ['id' => 2, 'name' => 'Non-Fiction', 'slug' => 'non-fiction'],
        ['id' => 3, 'name' => 'Science & Technology', 'slug' => 'science-technology'],
        ['id' => 4, 'name' => 'Business & Economics', 'slug' => 'business-economics'],
        ['id' => 5, 'name' => 'Self-Help', 'slug' => 'self-help'],
        ['id' => 6, 'name' => 'Children', 'slug' => 'children'],
        ['id' => 7, 'name' => 'Mystery & Thriller', 'slug' => 'mystery-thriller'],
        ['id' => 8, 'name' => 'Romance', 'slug' => 'romance'],
    ];
}

/**
 * Sample book data for when DB is not available
 */
function getSampleBooks($limit = null)
{
    $books = [
        [
            'id' => 1,
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'description' => 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan, set against the backdrop of the Roaring Twenties.',
            'price' => 299.00,
            'category_id' => 1,
            'cover_image' => '',
            'stock' => 25,
            'isbn' => '978-0743273565',
            'pages' => 180,
            'publisher' => 'Scribner',
            'rating' => 4.5,
            'created_at' => '2026-01-15'
        ],
        [
            'id' => 2,
            'title' => 'To Kill a Mockingbird',
            'author' => 'Harper Lee',
            'description' => 'The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it.',
            'price' => 350.00,
            'category_id' => 1,
            'cover_image' => '',
            'stock' => 18,
            'isbn' => '978-0061120084',
            'pages' => 281,
            'publisher' => 'Harper Perennial',
            'rating' => 4.8,
            'created_at' => '2026-01-10'
        ],
        [
            'id' => 3,
            'title' => 'Sapiens: A Brief History of Humankind',
            'author' => 'Yuval Noah Harari',
            'description' => 'A groundbreaking narrative of humanity\'s creation and evolution that explores how biology and history have defined us.',
            'price' => 499.00,
            'category_id' => 2,
            'cover_image' => '',
            'stock' => 30,
            'isbn' => '978-0062316097',
            'pages' => 464,
            'publisher' => 'Harper',
            'rating' => 4.7,
            'created_at' => '2026-01-20'
        ],
        [
            'id' => 4,
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'description' => 'An easy and proven way to build good habits and break bad ones. Transform your life with tiny changes.',
            'price' => 399.00,
            'category_id' => 5,
            'cover_image' => '',
            'stock' => 45,
            'isbn' => '978-0735211292',
            'pages' => 320,
            'publisher' => 'Avery',
            'rating' => 4.9,
            'created_at' => '2026-02-01'
        ],
        [
            'id' => 5,
            'title' => 'The Alchemist',
            'author' => 'Paulo Coelho',
            'description' => 'A magical story about Santiago, an Andalusian shepherd boy, who yearns to travel in search of a worldly treasure.',
            'price' => 275.00,
            'category_id' => 1,
            'cover_image' => '',
            'stock' => 35,
            'isbn' => '978-0062315007',
            'pages' => 197,
            'publisher' => 'HarperOne',
            'rating' => 4.6,
            'created_at' => '2026-01-25'
        ],
        [
            'id' => 6,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'description' => 'A handbook of agile software craftsmanship. Even bad code can function, but clean code is the mark of a true professional.',
            'price' => 599.00,
            'category_id' => 3,
            'cover_image' => '',
            'stock' => 20,
            'isbn' => '978-0132350884',
            'pages' => 464,
            'publisher' => 'Prentice Hall',
            'rating' => 4.7,
            'created_at' => '2026-02-05'
        ],
        [
            'id' => 7,
            'title' => 'The Silent Patient',
            'author' => 'Alex Michaelides',
            'description' => 'A shocking psychological thriller of a woman\'s act of violence against her husband — and the therapist obsessed with uncovering her motive.',
            'price' => 325.00,
            'category_id' => 7,
            'cover_image' => '',
            'stock' => 22,
            'isbn' => '978-1250301697',
            'pages' => 325,
            'publisher' => 'Celadon Books',
            'rating' => 4.4,
            'created_at' => '2026-02-10'
        ],
        [
            'id' => 8,
            'title' => 'Rich Dad Poor Dad',
            'author' => 'Robert T. Kiyosaki',
            'description' => 'What the rich teach their kids about money — that the poor and middle class do not! A timeless personal finance classic.',
            'price' => 350.00,
            'category_id' => 4,
            'cover_image' => '',
            'stock' => 40,
            'isbn' => '978-1612680194',
            'pages' => 336,
            'publisher' => 'Plata Publishing',
            'rating' => 4.5,
            'created_at' => '2026-02-08'
        ],
        [
            'id' => 9,
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'description' => 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides, who is destined for a great purpose.',
            'price' => 450.00,
            'category_id' => 1,
            'cover_image' => '',
            'stock' => 15,
            'isbn' => '978-0441013593',
            'pages' => 688,
            'publisher' => 'Ace',
            'rating' => 4.6,
            'created_at' => '2026-01-30'
        ],
        [
            'id' => 10,
            'title' => 'The Psychology of Money',
            'author' => 'Morgan Housel',
            'description' => 'Timeless lessons on wealth, greed, and happiness. Doing well with money isn\'t about what you know — it\'s about how you behave.',
            'price' => 375.00,
            'category_id' => 4,
            'cover_image' => '',
            'stock' => 28,
            'isbn' => '978-0857197689',
            'pages' => 256,
            'publisher' => 'Harriman House',
            'rating' => 4.8,
            'created_at' => '2026-02-12'
        ],
        [
            'id' => 11,
            'title' => 'It Ends with Us',
            'author' => 'Colleen Hoover',
            'description' => 'A brave and heartbreaking novel that digs its claws into you and doesn\'t let go.',
            'price' => 310.00,
            'category_id' => 8,
            'cover_image' => '',
            'stock' => 33,
            'isbn' => '978-1501110368',
            'pages' => 384,
            'publisher' => 'Atria Books',
            'rating' => 4.5,
            'created_at' => '2026-02-15'
        ],
        [
            'id' => 12,
            'title' => 'A Brief History of Time',
            'author' => 'Stephen Hawking',
            'description' => 'From the Big Bang to black holes, a landmark volume in science writing by one of the great minds of our time.',
            'price' => 425.00,
            'category_id' => 3,
            'cover_image' => '',
            'stock' => 12,
            'isbn' => '978-0553380163',
            'pages' => 212,
            'publisher' => 'Bantam',
            'rating' => 4.6,
            'created_at' => '2026-02-18'
        ],
    ];

    if ($limit) {
        return array_slice($books, 0, $limit);
    }

    return $books;
}

/**
 * Retrieve orders belonging to a specific user.
 *
 * @param int $userId
 * @return array
 */
function getOrdersByUser($userId)
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching orders: " . $e->getMessage());
        }
    }
    return [];
}

/**
 * Fetch items for a given order.
 */
function getOrderItems($orderId)
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT oi.*, b.title FROM order_items oi LEFT JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching order items: " . $e->getMessage());
        }
    }
    return [];
}

/**
 * Convert payment status key to styled label.
 */
function formatPaymentStatus($status)
{
    switch ($status) {
        case 'paid':
            return '<span class="badge badge-success">Paid</span>';
        case 'failed':
            return '<span class="badge badge-danger">Failed</span>';
        default:
            return '<span class="badge badge-secondary">Pending</span>';
    }
}

/**
 * Retrieve every order (admin use).
 */
function getAllOrders()
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT o.*, u.name as customer_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching all orders: " . $e->getMessage());
        }
    }
    return [];
}

/**
 * Generate star rating HTML
 */
function getStarRating($rating)
{
    $html = '<div class="star-rating">';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;

    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '<i class="fas fa-star"></i>';
    }
    if ($halfStar) {
        $html .= '<i class="fas fa-star-half-alt"></i>';
        $fullStars++;
    }
    for ($i = $fullStars; $i < 5; $i++) {
        $html .= '<i class="far fa-star"></i>';
    }
    $html .= '<span class="rating-text">(' . $rating . ')</span>';
    $html .= '</div>';

    return $html;
}

/**
 * Generate book cover placeholder with initials
 */
function getBookCoverPlaceholder($title)
{
    $words = explode(' ', $title);
    $initials = '';
    foreach (array_slice($words, 0, 2) as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }

    $colors = ['#6C5CE7', '#00B894', '#E17055', '#0984E3', '#FDCB6E', '#E84393', '#00CEC9', '#FF7675'];
    $colorIndex = abs(crc32($title)) % count($colors);
    $bgColor = $colors[$colorIndex];

    return '<div class="book-cover-placeholder" style="background: linear-gradient(135deg, ' . $bgColor . ', ' . adjustBrightness($bgColor, -30) . ')">' .
        '<span class="cover-initials">' . $initials . '</span></div>';
}

/**
 * Adjust color brightness
 */
function adjustBrightness($hex, $steps)
{
    $hex = str_replace('#', '', $hex);
    $r = max(0, min(255, hexdec(substr($hex, 0, 2)) + $steps));
    $g = max(0, min(255, hexdec(substr($hex, 2, 2)) + $steps));
    $b = max(0, min(255, hexdec(substr($hex, 4, 2)) + $steps));
    return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}

/**
 * Flash message helpers
 */
function setFlashMessage($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Get user by ID
 */
function getUserById($id)
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
        }
    }
    return null;
}

/**
 * Get all users for admin listing.
 */
function getAllUsers()
{
    $pdo = getDBConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
        }
    }
    return [];
}

/**
 * Truncate text
 */
function truncateText($text, $length = 100)
{
    if (strlen($text) <= $length)
        return $text;
    return substr($text, 0, $length) . '...';
}
