<?php
// dashboard.php - main user dashboard
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Dashboard';

$uid = (int)$_SESSION['user_id'];

// Counts for the dashboard tiles.
$listings_count  = $conn->query("SELECT COUNT(*) c FROM products WHERE user_id = $uid")->fetch_assoc()['c'];
$purchases_count = $conn->query("SELECT COUNT(*) c FROM orders   WHERE user_id = $uid")->fetch_assoc()['c'];
$cart_count      = $conn->query("SELECT COUNT(*) c FROM cart     WHERE user_id = $uid")->fetch_assoc()['c'];

// Recent activity = latest listings + orders for this user.
$activity = [];
$r = $conn->query("SELECT title, created_at FROM products WHERE user_id=$uid ORDER BY created_at DESC LIMIT 5");
while ($row = $r->fetch_assoc()) $activity[] = ['type' => 'Listed', 'text' => $row['title'], 'when' => $row['created_at']];
$r = $conn->query("SELECT id, total, created_at FROM orders WHERE user_id=$uid ORDER BY created_at DESC LIMIT 5");
while ($row = $r->fetch_assoc()) $activity[] = ['type' => 'Order',  'text' => 'Order #' . $row['id'] . ' for R ' . number_format($row['total'],2), 'when' => $row['created_at']];
usort($activity, fn($a,$b) => strcmp($b['when'], $a['when']));

include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <div>
    <h1 style="margin-bottom:18px;">Welcome, <?= e($_SESSION['user_name']) ?>!</h1>

    <div class="dash-tiles">
      <div class="dash-tile"><h3>My Listings</h3><div class="num"><?= $listings_count ?></div></div>
      <div class="dash-tile"><h3>Purchases</h3><div class="num"><?= $purchases_count ?></div></div>
      <div class="dash-tile alt"><h3>Cart Items</h3><div class="num"><?= $cart_count ?></div></div>
    </div>

    <div class="grid grid-2">
      <div class="card">
        <h2 style="color:var(--green-dark);margin-bottom:8px;">List a new item</h2>
        <p style="color:var(--muted);margin-bottom:14px;">Have something to sell? Create a listing in under a minute.</p>
        <a href="<?= base_url('sell.php') ?>" class="btn btn-primary">+ List New Item</a>
      </div>
      <div class="card">
        <h2 style="color:var(--green-dark);margin-bottom:8px;">Browse the shop</h2>
        <p style="color:var(--muted);margin-bottom:14px;">Discover items listed by other PhandaHub members.</p>
        <a href="<?= base_url('shop.php') ?>" class="btn btn-outline">Browse Shop</a>
      </div>
    </div>

    <h2 class="section-title">Recent activity</h2>
    <div class="card">
      <?php if (!$activity): ?>
        <div class="empty">No activity yet. Start by listing an item or browsing the shop.</div>
      <?php else: ?>
        <div class="table-wrap"><table class="table">
          <tr><th>Type</th><th>Details</th><th>When</th></tr>
          <?php foreach (array_slice($activity, 0, 6) as $a): ?>
            <tr><td><?= e($a['type']) ?></td><td><?= e($a['text']) ?></td><td><?= e($a['when']) ?></td></tr>
          <?php endforeach; ?>
        </table></div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
