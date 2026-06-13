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
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a href="<?= base_url('index.php') ?>" class="brand">
      <img src="<?= base_url('images/logo.png') ?>" alt="PhandaHub logo">
    </a>
    <nav class="nav-links">
      <a href="<?= base_url('index.php') ?>">Home</a>
      <a href="<?= base_url('shop.php') ?>">Shop</a>
      <?php if (is_logged_in()): ?>
        <a href="<?= base_url('dashboard.php') ?>">Dashboard</a>
        <a href="<?= base_url('cart.php') ?>">Cart</a>
        <a href="<?= base_url('logout.php') ?>" class="btn btn-outline">Logout</a>
      <?php else: ?>
        <a href="<?= base_url('login.php') ?>" class="btn btn-outline">Sign In</a>
        <a href="<?= base_url('register.php') ?>" class="btn btn-primary">Register</a>
      <?php endif; ?>
      <a href="<?= base_url('admin/login.php') ?>" class="btn btn-ghost">Admin</a>
    </nav>
  </div>
</header>
<?php if ($flash): ?>
  <div class="flash flash-<?= e($flash['type']) ?>"><div class="container"><?= e($flash['msg']) ?></div></div>
<?php endif; ?>
<main class="container main">
