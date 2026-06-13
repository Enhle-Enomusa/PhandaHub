<?php
// includes/sidebar.php - sidebar shared across user dashboard pages
require_once __DIR__ . '/auth.php';
?>
<aside class="sidebar">
  <a href="<?= base_url('index.php') ?>" class="sidebar-brand">
    <img src="<?= base_url('images/logo.png') ?>" alt="PhandaHub">
  </a>
  <nav class="sidebar-nav">
    <a href="<?= base_url('dashboard.php') ?>">Dashboard</a>
    <a href="<?= base_url('my_listings.php') ?>">My Listings</a>
    <a href="<?= base_url('purchases.php') ?>">Purchases</a>
    <a href="<?= base_url('profile.php') ?>">Profile</a>
    <a href="<?= base_url('sell.php') ?>">+ List New Item</a>
    <a href="<?= base_url('shop.php') ?>">Browse Shop</a>
    <a href="<?= base_url('logout.php') ?>" class="sidebar-logout">Logout</a>
  </nav>
</aside>
