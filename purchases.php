<?php
// purchases.php - the logged-in user's orders
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_login();
$page_title = 'Purchases';
$uid = (int)$_SESSION['user_id'];

$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
include __DIR__ . '/includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/includes/sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">My purchases</h1>

    <?php if ($orders->num_rows === 0): ?>
      <div class="empty card">You haven't made any purchases yet. <a href="<?= base_url('shop.php') ?>">Browse the shop</a>.</div>
    <?php else: ?>
      <?php while ($o = $orders->fetch_assoc()): ?>
        <div class="card" style="margin-bottom:16px;">
          <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:6px;">
            <strong>Order #<?= (int)$o['id'] ?></strong>
            <span style="color:var(--muted);"><?= e($o['created_at']) ?></span>
            <span class="btn btn-gold" style="cursor:default;"><?= e($o['status']) ?></span>
          </div>
          <div class="table-wrap" style="margin-top:10px;"><table class="table">
            <tr><th>Item</th><th>Price</th><th>Qty</th></tr>
            <?php
            $oi = $conn->query("SELECT * FROM order_items WHERE order_id={$o['id']}");
            while ($it = $oi->fetch_assoc()):
            ?>
              <tr><td><?= e($it['title']) ?></td><td>R <?= number_format($it['price'],2) ?></td><td><?= (int)$it['quantity'] ?></td></tr>
            <?php endwhile; ?>
            <tr><td colspan="2" style="text-align:right;font-weight:700;">Total</td><td style="color:var(--green);font-weight:700;">R <?= number_format($o['total'],2) ?></td></tr>
          </table></div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
