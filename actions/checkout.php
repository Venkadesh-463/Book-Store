<?php
/**
 * Checkout Action - BookHaven
 */
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'index.php?page=checkout');
    exit;
}

// CSRF validation
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlashMessage('error', 'Invalid request token.');
    redirect(BASE_URL . 'index.php?page=checkout');
    exit;
}

if (!isLoggedIn()) {
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect(BASE_URL . 'index.php?page=cart');
    exit;
}

$fullName = sanitize($_POST['full_name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$address = sanitize($_POST['address'] ?? '');
$city = sanitize($_POST['city'] ?? '');
$pincode = sanitize($_POST['pincode'] ?? '');
$paymentMethod = sanitize($_POST['payment_method'] ?? 'cod');

if (empty($fullName) || empty($phone) || empty($address) || empty($city) || empty($pincode)) {
    setFlashMessage('error', 'Please fill in all shipping details.');
    redirect(BASE_URL . 'index.php?page=checkout');
    exit;
}

// validate additional payment information as needed
$paymentDetails = [];
if ($paymentMethod === 'card') {
    $cardNumber = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $expiry = sanitize($_POST['card_expiry'] ?? '');
    $cvv = preg_replace('/\D/', '', $_POST['card_cvv'] ?? '');
    if (!preg_match('/^[0-9]{16}$/', $cardNumber) || !preg_match('/^[0-9]{2}\/([0-9]{2})$/', $expiry) || !preg_match('/^[0-9]{3,4}$/', $cvv)) {
        setFlashMessage('error', 'Please provide valid card details.');
        redirect(BASE_URL . 'index.php?page=checkout');
        exit;
    }
    $paymentDetails = ['number' => $cardNumber, 'expiry' => $expiry, 'cvv' => $cvv];
}
elseif ($paymentMethod === 'upi') {
    $upiId = sanitize($_POST['upi_id'] ?? '');
    if (!$upiId) {
        setFlashMessage('error', 'Please provide your UPI ID.');
        redirect(BASE_URL . 'index.php?page=checkout');
        exit;
    }
    $paymentDetails = ['upi_id' => $upiId];
}
elseif ($paymentMethod === 'netbanking') {
    $bankName = sanitize($_POST['bank_name'] ?? '');
    if (!$bankName) {
        setFlashMessage('error', 'Please select a bank for Net Banking.');
        redirect(BASE_URL . 'index.php?page=checkout');
        exit;
    }
    $paymentDetails = ['bank_name' => $bankName];
}
elseif ($paymentMethod === 'wallet') {
    $walletName = sanitize($_POST['wallet_name'] ?? '');
    $walletPhone = sanitize($_POST['wallet_phone'] ?? '');
    if (!$walletName || !$walletPhone) {
        setFlashMessage('error', 'Please provide wallet details.');
        redirect(BASE_URL . 'index.php?page=checkout');
        exit;
    }
    $paymentDetails = ['wallet_name' => $walletName, 'wallet_phone' => $walletPhone];
}

$shippingAddress = "$fullName\n$address\n$city - $pincode\nPhone: $phone";
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
$total = $subtotal + 49; // shipping

$pdo = getDBConnection();
if ($pdo) {
    try {
        // attempt to charge customer (or simulate)
        $paymentResult = processPayment($paymentMethod, $paymentDetails, $total);
        $paymentStatus = $paymentResult['success'] ? 'paid' : 'failed';
        $transactionId = $paymentResult['transaction_id'] ?? null;

        if (!$paymentResult['success'] && $paymentMethod !== 'cod') {
            // abort order if electronic payment failed
            setFlashMessage('error', 'Payment failed: ' . $paymentResult['message']);
            redirect(BASE_URL . 'index.php?page=checkout');
            exit;
        }

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO orders 
            (user_id, total_amount, status, payment_status, transaction_id, shipping_address, payment_method, created_at)
            VALUES (?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            $_SESSION['user_id'],
            $total,
            'processing',
            $paymentStatus,
            $transactionId,
            $shippingAddress,
            $paymentMethod
        ]);
        $orderId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?,?,?,?)");
        foreach ($cart as $item) {
            $stmt->execute([$orderId, $item['book_id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];
        setFlashMessage('success', 'Order #' . $orderId . ' placed successfully! Thank you for your purchase.');
        redirect(BASE_URL . 'index.php?page=profile');
        exit;
    }
    catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Checkout error: " . $e->getMessage());
    }
}

// Demo mode
$_SESSION['cart'] = [];
setFlashMessage('success', 'Order placed successfully (demo mode)! Thank you for your purchase.');
redirect(BASE_URL . 'index.php?page=profile');
