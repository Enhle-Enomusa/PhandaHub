<?php
// includes/auth.php - session helpers and access guards
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() { return !empty($_SESSION['user_id']); }
function is_admin()     { return !empty($_SESSION['admin_id']); }

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . base_url('pages/login.php?msg=login_required'));
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}

// Build correct URLs for a site installed directly under htdocs.
function base_url($path = '') {
    $path = ltrim((string)$path, '/');

    // Full external URL: leave it unchanged.
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    // These folders/files live directly under htdocs.
    $root_items = ['admin/', 'pages/', 'css/', 'js/', 'images/', 'uploads/', 'db/', 'index.php'];
    foreach ($root_items as $item) {
        if ($path === rtrim($item, '/') || substr($path, 0, strlen($item)) === $item) {
            return '/' . $path;
        }
    }

    // If a pages/*.php file calls base_url('shop.php'), keep it in /pages.
    $current = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (strpos($current, '/pages/') !== false) {
        return '/pages/' . $path;
    }

    // If an admin/*.php file calls base_url('dashboard.php'), keep it in /admin.
    if (strpos($current, '/admin/') !== false) {
        return '/admin/' . $path;
    }

    return '/' . $path;
}

// Simple flash-message helpers.
function set_flash($type, $msg) { $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; }
function get_flash() {
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// Quick HTML-escape helper.
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
