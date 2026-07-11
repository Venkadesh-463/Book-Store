<?php
// Flash message display
$flash = getFlashMessage();
if ($flash): ?>
<div class="flash-message flash-<?php echo $flash['type']; ?>" id="flashMessage">
    <div class="flash-content">
        <i class="fas fa-<?php echo $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-circle' : 'info-circle'); ?>"></i>
        <span><?php echo $flash['message']; ?></span>
    </div>
    <button class="flash-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
<?php
endif; ?>

<!-- Navbar -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <!-- Logo -->
        <a href="<?php echo BASE_URL; ?>" class="nav-logo">
            <i class="fas fa-book-open"></i>
            <span>Book<span class="logo-accent">Haven</span></span>
        </a>

        <!-- Search Bar -->
        <div class="nav-search">
            <form action="<?php echo BASE_URL; ?>index.php" method="GET" class="search-form">
                <input type="hidden" name="page" value="shop">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Search books, authors, genres..." 
                           class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
            </form>
        </div>

        <!-- Nav Links -->
        <div class="nav-links" id="navLinks">
            <a href="<?php echo BASE_URL; ?>" class="nav-link <?php echo(!isset($page) || $page === 'home') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i><span>Home</span>
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?page=shop" class="nav-link <?php echo(isset($page) && $page === 'shop') ? 'active' : ''; ?>">
                <i class="fas fa-store"></i><span>Shop</span>
            </a>
            <a href="<?php echo BASE_URL; ?>index.php?page=contact" class="nav-link <?php echo(isset($page) && $page === 'contact') ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i><span>Contact</span>
            </a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?php echo BASE_URL; ?>index.php?page=cart" class="nav-link cart-link <?php echo(isset($page) && $page === 'cart') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Cart</span>
                    <?php $cartCount = getCartCount();
    if ($cartCount > 0): ?>
                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php
    endif; ?>
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?page=wishlist" class="nav-link wishlist-link <?php echo(isset($page) && $page === 'wishlist') ? 'active' : ''; ?>">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                    <?php $wishCount = getWishlistCount();
    if ($wishCount > 0): ?>
                    <span class="cart-badge"><?php echo $wishCount; ?></span>
                    <?php
    endif; ?>
                </a>
                
                <div class="nav-dropdown">
                    <button class="nav-link dropdown-toggle">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Account'; ?></span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="<?php echo BASE_URL; ?>index.php?page=profile" class="dropdown-item">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <?php if (isAdmin()): ?>
                        <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="dropdown-item">
                            <i class="fas fa-tachometer-alt"></i> Admin Panel
                        </a>
                        <?php
    endif; ?>
                        <hr class="dropdown-divider">
                        <a href="<?php echo BASE_URL; ?>actions/logout.php" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            <?php
else: ?>
                <a href="<?php echo BASE_URL; ?>index.php?page=login" class="nav-link <?php echo(isset($page) && $page === 'login') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-in-alt"></i><span>Login</span>
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?page=register" class="btn btn-primary btn-sm nav-btn">
                    <i class="fas fa-user-plus"></i> Sign Up
                </a>
            <?php
endif; ?>
        </div>

        <!-- Mobile Toggle -->
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span class="hamburger"></span>
        </button>
    </div>
</nav>

<!-- Main Content Wrapper -->
<main class="main-content">
