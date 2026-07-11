<?php $pageTitle = 'Register'; ?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon"><i class="fas fa-user-plus"></i></div>
            <h2>Create Account</h2>
            <p>Join BookHaven and start your reading journey</p>
        </div>

        <form id="registerForm" method="POST" action="<?php echo BASE_URL; ?>actions/register.php" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <div class="input-icon-wrapper">
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-icon-wrapper">
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-icon-wrapper">
                    <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-icon-wrapper">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                    <i class="fas fa-shield-alt input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:var(--space-md);">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="auth-divider">or sign up with</div>

        <div class="social-login">
            <button class="social-btn"><i class="fab fa-google"></i> Google</button>
            <button class="social-btn"><i class="fab fa-facebook-f"></i> Facebook</button>
        </div>

        <div class="auth-footer">
            Already have an account? <a href="index.php?page=login">Sign in</a>
        </div>
    </div>
</div>
