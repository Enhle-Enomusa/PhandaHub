# PhandaHub Marketplace

A full-stack C2C e-commerce prototype built with **HTML, CSS, JavaScript (front-end validation), PHP (back-end) and MySQL**. Green & white South African theme.

## What's inside

| Folder | Purpose |
|--------|---------|
| `/css`        | `style.css` global styles |
| `/js`         | `script.js` client-side validation |
| `/includes`   | DB connection, session/auth helpers, header/footer/sidebar partials |
| `/images`     | Logo + sample images (`/images/products`) |
| `/uploads`    | Where uploaded product images are saved (auto-created, must be writable) |
| `/db`         | `phandahub.sql` schema + sample data |
| `/admin`      | Admin login, dashboard, users & listings management |

## Pages

Public: `index.php`, `register.php`, `login.php`, `shop.php`, `product.php`
User (login required): `dashboard.php`, `sell.php`, `my_listings.php`, `cart.php`, `payment.php`, `confirmation.php`, `purchases.php`, `profile.php`, `logout.php`
Admin (admin login required): `admin/login.php`, `admin/dashboard.php`, `admin/users.php`, `admin/listings.php`, `admin/logout.php`

## Install with XAMPP

1. Install **XAMPP** (https://www.apachefriends.org/) and start **Apache** and **MySQL** from the XAMPP control panel.
2. Copy the entire `phandahub` folder into `C:\xampp\htdocs\` (Windows) or `/Applications/XAMPP/htdocs/` (Mac).
3. Open **phpMyAdmin** at http://localhost/phpmyadmin.
4. Click **Import**, choose `phandahub/db/phandahub.sql`, then click **Go**. This creates the `phandahub` database, all tables, and inserts sample data.
5. Open http://localhost/phandahub/ in your browser.

If your MySQL root password isn't blank, edit `includes/db.php` and update `$DB_USER` / `$DB_PASS`.

## Demo accounts

| Role | Login | Password |
|------|-------|----------|
| User  | `thabo@demo.com`  | `password123` |
| User  | `lerato@demo.com` | `password123` |
| Admin | `admin`           | `admin123`    |

## Features

- Register / login with **password hashing** (`password_hash` / `password_verify`)
- **PHP sessions** protect dashboard, cart, purchases, profile and admin pages
- **Defensive validation** on both the client (JS) and the server (PHP)
- Create listings with optional **image upload** (`/uploads`) or placeholder fallback
- Browse / search shop, view product detail, add to cart, checkout
- **Realistic fake payment gateway** (simulated PhandaPay) → order confirmation
- "My Listings", "Purchases", "Profile" + **wallet** section
- **Admin panel**: total user count, view all users (delete), view/edit/delete all listings
- Responsive layout for desktop / tablet / mobile
- Clear success and error flash messages

## Notes

- The `uploads/` folder must be writable by the web server. On most XAMPP installs the default permissions already allow this. On Linux/Mac you may need `chmod 775 uploads`.
- Sample product images use `picsum.photos` (random photos). Upload your own when creating real listings; they will be stored in `/uploads`.
- This is an **academic prototype** — the payment screen does not contact a real payment processor.
