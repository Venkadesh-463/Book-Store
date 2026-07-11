<?php $pageTitle = 'Login'; ?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon"><i class="fas fa-sign-in-alt"></i></div>
            <h2>Welcome Back</h2>
            <p>Sign in to your BookHaven account</p>
        </div>

        <form id="loginForm" method="POST" action="<?php echo BASE_URL; ?>actions/login.php" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
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
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>
                </div>
            </div>

            <div class="auth-options">
                <label class="remember-me"><input type="checkbox" name="remember"> Remember me</label>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="auth-divider">or continue with</div>

        <div class="social-login">
            <button class="social-btn"><i class="fab fa-google"></i> Google</button>
            <button class="social-btn"><i class="fab fa-facebook-f"></i> Facebook</button>
        </div>

        <div class="auth-footer">
            Don't have an account? <a href="index.php?page=register">Create one</a>
        </div>
    </div>
</div>
