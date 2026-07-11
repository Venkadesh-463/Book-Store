<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BookHaven - Your gateway to knowledge. Discover thousands of books across every genre. Shop bestsellers, new arrivals, and timeless classics.">
    <meta name="keywords" content="books, online bookstore, buy books, fiction, non-fiction, bestsellers">
    <meta name="author" content="BookHaven">
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo BASE_URL; ?>public/images/icons/favicon.svg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/main.css">
    <?php if (isset($page) && in_array($page, ['login', 'register'])): ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/auth.css">
    <?php
endif; ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/responsive.css">
    <!-- expose base URL & CSRF token for JS -->
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <meta name="csrf-token" content="<?php echo generateCsrfToken(); ?>">
    <script>
        window.wishlistIds = <?php echo json_encode(getWishlist()); ?>;
    </script>
</head>
<body>
