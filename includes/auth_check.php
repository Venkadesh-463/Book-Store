<?php
/**
 * Authentication Check
 * Redirects to login if user is not authenticated
 */

if (!isLoggedIn()) {
    setFlashMessage('warning', 'Please log in to access this page.');
    redirect(BASE_URL . 'index.php?page=login');
    exit;
}
