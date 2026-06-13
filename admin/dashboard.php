<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_admin();
$page_title = 'Admin Dashboard';

$user_count    = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$product_count = $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];
$order_count   = $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
$revenue       = $conn->query("SELECT COALESCE(SUM(total),0) t FROM orders")->fetch_assoc()['t'];

include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">Admin overview</h1>
    <div class="dash-tiles">
      <div class="dash-tile"><h3>Total users</h3><div class="num"><?= $user_count ?></div></div>
      <div class="dash-tile"><h3>Total listings</h3><div class="num"><?= $product_count ?></div></div>
      <div class="dash-tile alt"><h3>Total orders</h3><div class="num"><?= $order_count ?></div></div>
    </div>
    <div class="card" style="margin-bottom:20px;">
      <h2 style="color:var(--green-dark);">Total revenue processed</h2>
      <div style="font-size:30px;color:var(--green);font-weight:700;">R <?= number_format($revenue,2) ?></div>
    </div>
    <div class="grid grid-2">
      <a href="<?= base_url('admin/users.php') ?>" class="card" style="text-decoration:none;color:inherit;">
        <h2 style="color:var(--green-dark);">Manage users &rarr;</h2>
        <p style="color:var(--muted);">View and remove user accounts.</p>
      </a>
      <a href="<?= base_url('admin/listings.php') ?>" class="card" style="text-decoration:none;color:inherit;">
        <h2 style="color:var(--green-dark);">Manage listings &rarr;</h2>
        <p style="color:var(--muted);">View, edit and delete all product listings.</p>
      </a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
