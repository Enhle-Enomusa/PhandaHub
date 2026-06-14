<?php
// includes/header.php - top navigation, shared on public pages
require_once __DIR__ . '/auth.php';
$flash = get_flash();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($page_title) ? e($page_title) . ' | PhandaHub' : 'PhandaHub Marketplace' ?></title>
<link rel="stylesheet" href="/css/style.css?v=3">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a href="/index.php" class="brand">
            <img src="/images/logo.png?v=2" alt="PhandaHub logo">
        </a>

        <nav class="nav-links">
            <a href="/index.php">Home</a>
            <a href="/pages/shop.php">Shop</a>

            <?php if (is_logged_in()): ?>
                <a href="/pages/dashboard.php">Dashboard</a>
                <a href="/pages/cart.php">Cart</a>
                <a href="/pages/logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="/pages/login.php" class="btn btn-outline">Sign In</a>
                <a href="/pages/register.php" class="btn btn-primary">Register</a>
            <?php endif; ?>

            <a href="/admin/login.php" class="btn btn-outline">Admin</a>
        </nav>
    </div>
</header>
<?php if ($flash): ?>
  <div class="flash flash-<?= e($flash['type']) ?>"><div class="container"><?= e($flash['msg']) ?></div></div>
<?php endif; ?>
<main class="container main">
