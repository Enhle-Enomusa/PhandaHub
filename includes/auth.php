<?php
// includes/auth.php - session helpers and access guards
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() { return !empty($_SESSION['user_id']); }
function is_admin()     { return !empty($_SESSION['admin_id']); }

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . base_url('login.php?msg=login_required'));
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: ' . base_url('admin/login.php'));
        exit;
    }
}

// Build a URL relative to project root so links work in any subfolder install.
function base_url($path = '') {
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // If we are inside /admin, step one folder up.
    if (basename($script) === 'admin') $script = dirname($script);
    if ($script === '/' || $script === '\\') $script = '';
    return $script . '/' . ltrim($path, '/');
}

// Simple flash-message helpers.
function set_flash($type, $msg) { $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; }
function get_flash() {
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// Quick HTML-escape helper.
function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
