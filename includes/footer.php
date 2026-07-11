</main>
<!-- End Main Content -->

<!-- Footer -->
<footer class="footer">
    <div class="footer-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="currentColor" d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,64C960,75,1056,85,1152,80C1248,75,1344,53,1392,42.7L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
        </svg>
    </div>
    
    <div class="footer-content">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-col footer-brand">
                    <a href="<?php echo BASE_URL; ?>" class="footer-logo">
                        <i class="fas fa-book-open"></i>
                        <span>Book<span class="logo-accent">Haven</span></span>
                    </a>
                    <p class="footer-desc">Your gateway to knowledge. Discover thousands of books across every genre. From bestsellers to hidden gems.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=shop"><i class="fas fa-chevron-right"></i> Shop</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=cart"><i class="fas fa-chevron-right"></i> Cart</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="footer-col">
                    <h4 class="footer-title">Categories</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=shop&category=1"><i class="fas fa-chevron-right"></i> Fiction</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=shop&category=2"><i class="fas fa-chevron-right"></i> Non-Fiction</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=shop&category=3"><i class="fas fa-chevron-right"></i> Science</a></li>
                        <li><a href="<?php echo BASE_URL; ?>index.php?page=shop&category=5"><i class="fas fa-chevron-right"></i> Self-Help</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-col">
                    <h4 class="footer-title">Newsletter</h4>
                    <p class="footer-desc">Subscribe for new releases and exclusive offers.</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Subscribed successfully!', 'success');">
                        <div class="newsletter-input-group">
                            <input type="email" placeholder="Your email address" class="newsletter-input" required>
                            <button type="submit" class="newsletter-btn"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i> 123 Book Street, Chennai</p>
                        <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                        <p><i class="fas fa-envelope"></i> hello@bookhaven.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. Built with <i class="fas fa-heart" style="color: #e84393;"></i></p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
<script src="<?php echo BASE_URL; ?>public/js/cart.js"></script>
<?php if (isset($page) && in_array($page, ['login', 'register', 'checkout'])): ?>
<script src="<?php echo BASE_URL; ?>public/js/validation.js"></script>
<?php
endif; ?>

</body>
</html>
