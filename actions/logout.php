<?php
require_once __DIR__ . '/../config/config.php';
session_destroy();
session_start();
setFlashMessage('success', 'You have been logged out successfully.');
redirect(BASE_URL . 'index.php?page=login');
