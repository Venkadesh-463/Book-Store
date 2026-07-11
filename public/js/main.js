/**
 * Main JavaScript - BookHaven
 */

document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initDropdowns();
    initScrollEffects();
    initFlashMessages();
});

/* --- Navbar --- */
function initNavbar() {
    const toggle = document.getElementById('navToggle');
    const links = document.getElementById('navLinks');
    const hamburger = toggle?.querySelector('.hamburger');

    if (toggle && links) {
        toggle.addEventListener('click', () => {
            links.classList.toggle('open');
            hamburger?.classList.toggle('active');
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !links.contains(e.target)) {
                links.classList.remove('open');
                hamburger?.classList.remove('active');
            }
        });
    }
}

/* --- Dropdowns (mobile) --- */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    dropdowns.forEach(dd => {
        const toggleBtn = dd.querySelector('.dropdown-toggle');
        if (toggleBtn && window.innerWidth <= 768) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                dd.classList.toggle('open');
            });
        }
    });
}

/* --- Scroll Effects --- */
function initScrollEffects() {
    const navbar = document.getElementById('navbar');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        if (navbar) {
            navbar.classList.toggle('scrolled', currentScroll > 50);
        }
        lastScroll = currentScroll;
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

/* --- Flash Messages --- */
function initFlashMessages() {
    const flash = document.getElementById('flashMessage');
    if (flash) {
        setTimeout(() => {
            flash.style.animation = 'slideOutRight 0.4s ease forwards';
            setTimeout(() => flash.remove(), 400);
        }, 5000);
    }
}

/* --- Toast Notifications --- */
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    toast.innerHTML = `<i class="fas fa-${icon}"></i><span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.4s ease forwards';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

/* --- Slide out animation (add to stylesheet dynamically) --- */
const style = document.createElement('style');
style.textContent = `@keyframes slideOutRight { to { transform: translateX(120%); opacity: 0; } }`;
document.head.appendChild(style);

/* --- Filter Toggle (mobile) --- */
function toggleFilter() {
    const sidebar = document.querySelector('.filter-sidebar');
    if (sidebar) sidebar.classList.toggle('open');
}

/* --- Quantity Controls --- */
function updateQuantity(bookId, change) {
    const input = document.querySelector(`.qty-input[data-book-id="${bookId}"]`);
    if (input) {
        let val = parseInt(input.value) + change;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
    }
}

/* --- Admin Sidebar Toggle (mobile) --- */
function toggleAdminSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    if (sidebar) sidebar.classList.toggle('open');
}

/* --- Confirm Delete --- */
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}
