# 📚 BookHaven — Online Book Store

A full-featured online book store built with **PHP + MySQL**, featuring a modern dark-themed UI with glassmorphism effects, responsive design, and a complete admin panel.

## ✨ Features

- **🏠 Home** — Hero section, featured books, categories, testimonials
- **🛍️ Shop** — Book browsing with filters (category, price, rating), search
- **📖 Book Details** — Cover, description, meta info, reviews, related books
- **🛒 Cart** — Session-based cart with AJAX quantity updates
- **💳 Checkout** — Shipping info, flexible payment options (COD, UPI, card) with validation
- **🔐 Auth** — Login/Register with glassmorphism cards, password toggle
- **👤 Profile** — Edit profile, order history
- **📬 Contact** — Contact form with info cards
- **⚙️ Admin Panel** — Dashboard, manage books (CRUD), orders, users

## 🚀 Quick Start

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)

### Setup

1. **Clone/Copy** the project to your XAMPP `htdocs` directory:
   ```
   C:\xampp\htdocs\Online Book Store\
   ```

2. **Start XAMPP** — Enable Apache and MySQL

3. **Create Database** (optional — app works with sample data):
   ```sql
   CREATE DATABASE online_bookstore;
   USE online_bookstore;
   ```
   Import `database/schema.sql` or use the schema below.
   For the full book list, also import `database/book-inserts.sql` after schema creation.

4. **Open in browser**:
   ```
   http://localhost/Online%20Book%20Store/
   ```

## 📦 GitHub Ready
This project is prepared for GitHub upload with a clean `.gitignore` and a single initial commit.

To publish on GitHub:
```bash
cd "C:\xampp\htdocs\Online Book Store"
git remote add origin https://github.com/<your-username>/<repo-name>.git
git push -u origin master
```

If GitHub uses `main` as the default branch, run:
```bash
git branch -M main
git push -u origin main
```

### What is included for GitHub
- source code in `actions/`, `admin/`, `config/`, `includes/`, `pages/`, `public/`
- database schema in `database/schema.sql`
- full book seed file in `database/book-inserts.sql`
- `.gitignore` to exclude log and editor files

### Important notes
- Do not upload `logs/error.log`
- Do not upload personal IDE workspace files

### Demo Credentials
| Role  | Email                  | Password  |
|-------|------------------------|-----------|
| Admin | admin@bookhaven.com    | admin123  |
| User  | user@bookhaven.com     | user123   |

## 🗄️ Database Schema

The full database schema and seed data are stored in the `database/` folder.

- `database/schema.sql` — database tables and default users/categories
- `database/book-inserts.sql` — full book seed inserts

Use those files to import the schema instead of embedding SQL directly in this README.

## � Payment Integration

The application ships with a **stubbed payment helper** (`includes/payment.php`) that simulates successful
transactions for card and UPI methods. To go live, replace the `processPayment()` implementation with
a real integration (Stripe, Razorpay, PayPal, etc.) and add any necessary API keys or SDKs via Composer.

Configuration constants (e.g. `PAYMENT_GATEWAY`, `STRIPE_SECRET_KEY`) can be added to `config/config.php`.

---

## �📁 Project Structure

```
online-book-store/
├── index.php          # Front controller
├── config/            # DB & app configuration
├── includes/          # Shared components (header, navbar, footer)
├── pages/             # Public pages (home, shop, cart, etc.)
├── admin/             # Admin panel
├── actions/           # Form handlers (login, register, cart, etc.)
├── public/css/        # Stylesheets
├── public/js/         # JavaScript
├── uploads/           # User uploads
└── logs/              # Error logs
```

## 🎨 Design

- **Dark theme** with vibrant purple/teal accents
- **Glassmorphism** auth cards
- **Responsive** — mobile, tablet, desktop
- **Inter + Playfair Display** fonts
- **Font Awesome** icons
- **CSS custom properties** design system

## 📄 License

This project is for educational purposes. Built with ❤️
