/**
 * Form Validation - BookHaven
 */

document.addEventListener('DOMContentLoaded', () => {
    initValidation();
    initPasswordToggle();
});

function initValidation() {
    // Login Form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            let valid = true;
            clearErrors(this);

            const email = this.querySelector('[name="email"]');
            const password = this.querySelector('[name="password"]');

            if (!email.value.trim()) { showError(email, 'Email is required'); valid = false; }
            else if (!isValidEmail(email.value)) { showError(email, 'Please enter a valid email'); valid = false; }

            if (!password.value) { showError(password, 'Password is required'); valid = false; }

            if (!valid) e.preventDefault();
        });
    }

    // Register Form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            let valid = true;
            clearErrors(this);

            const name = this.querySelector('[name="name"]');
            const email = this.querySelector('[name="email"]');
            const password = this.querySelector('[name="password"]');
            const confirm = this.querySelector('[name="confirm_password"]');

            if (!name.value.trim()) { showError(name, 'Name is required'); valid = false; }
            else if (name.value.trim().length < 2) { showError(name, 'Name must be at least 2 characters'); valid = false; }

            if (!email.value.trim()) { showError(email, 'Email is required'); valid = false; }
            else if (!isValidEmail(email.value)) { showError(email, 'Please enter a valid email'); valid = false; }

            if (!password.value) { showError(password, 'Password is required'); valid = false; }
            else if (password.value.length < 6) { showError(password, 'Password must be at least 6 characters'); valid = false; }

            if (password.value !== confirm.value) { showError(confirm, 'Passwords do not match'); valid = false; }

            if (!valid) e.preventDefault();
        });
    }

    // Checkout Form
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            let valid = true;
            clearErrors(this);

            const requiredFields = this.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showError(field, `${field.dataset.label || 'This field'} is required`);
                    valid = false;
                }
            });

            const phone = this.querySelector('[name="phone"]');
            if (phone && phone.value && !isValidPhone(phone.value)) {
                showError(phone, 'Please enter a valid phone number'); valid = false;
            }

            // payment-specific checks
            const method = this.querySelector('input[name="payment_method"]:checked')?.value;
            if (method === 'card') {
                const card = this.querySelector('[name="card_number"]');
                const exp = this.querySelector('[name="card_expiry"]');
                const cvv = this.querySelector('[name="card_cvv"]');
                if (card && !/^[0-9]{16}$/.test(card.value.replace(/\s+/g,''))) { showError(card, 'Enter a 16-digit card number'); valid = false; }
                if (exp && !/^[0-9]{2}\/[0-9]{2}$/.test(exp.value)) { showError(exp, 'Expiry must be MM/YY'); valid = false; }
                if (cvv && !/^[0-9]{3,4}$/.test(cvv.value)) { showError(cvv, 'Invalid CVV'); valid = false; }
            } else if (method === 'upi') {
                const upi = this.querySelector('[name="upi_id"]');
                if (upi && !upi.value.trim()) { showError(upi, 'UPI ID required'); valid = false; }
            }

            if (!valid) e.preventDefault();
        });
    }

    // Contact Form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            let valid = true;
            clearErrors(this);

            const name = this.querySelector('[name="name"]');
            const email = this.querySelector('[name="email"]');
            const message = this.querySelector('[name="message"]');

            if (!name.value.trim()) { showError(name, 'Name is required'); valid = false; }
            if (!email.value.trim()) { showError(email, 'Email is required'); valid = false; }
            else if (!isValidEmail(email.value)) { showError(email, 'Invalid email format'); valid = false; }
            if (!message.value.trim()) { showError(message, 'Message is required'); valid = false; }

            if (!valid) e.preventDefault();
        });
    }
}

/* Password Toggle */
function initPasswordToggle() {
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.closest('.input-icon-wrapper').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
}

/* Helpers */
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPhone(phone) {
    return /^[0-9+\-\s()]{7,15}$/.test(phone);
}

function showError(input, message) {
    const group = input.closest('.form-group');
    if (group) {
        input.style.borderColor = 'var(--danger)';
        let errorEl = group.querySelector('.form-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'form-error';
            group.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }
}

function clearErrors(form) {
    form.querySelectorAll('.form-error').forEach(el => el.remove());
    form.querySelectorAll('.form-control').forEach(el => el.style.borderColor = '');
}
