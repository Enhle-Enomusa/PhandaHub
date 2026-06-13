<?php
// includes/admin_sidebar.php
require_once __DIR__ . '/auth.php';
?>
<aside class="sidebar sidebar-admin">
  <a href="<?= base_url('admin/dashboard.php') ?>" class="sidebar-brand">
    <img src="<?= base_url('images/logo.png') ?>" alt="PhandaHub Admin">
  </a>
  <nav class="sidebar-nav">
    <a href="<?= base_url('admin/dashboard.php') ?>">Dashboard</a>
    <a href="<?= base_url('admin/users.php') ?>">Users</a>
    <a href="<?= base_url('admin/listings.php') ?>">Listings</a>
    <a href="<?= base_url('admin/logout.php') ?>" class="sidebar-logout">Logout</a>
  </nav>
</aside>
