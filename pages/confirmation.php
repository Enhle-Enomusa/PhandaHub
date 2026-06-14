<?php
// confirmation.php - order success
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Order Confirmed';

$order_id = (int)($_GET['order'] ?? 0);
$uid = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->bind_param('ii', $order_id, $uid); $stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    set_flash('error', 'Order not found.');
    header('Location: ' . base_url('dashboard.php')); exit;
}

$items = $conn->query("SELECT * FROM order_items WHERE order_id=$order_id");

include __DIR__ . '/../includes/header.php';
?>
<div class="card form-card wide" style="text-align:center;">
  <div class="tick">✓</div>
  <h1 style="color:var(--green-dark);">Payment successful!</h1>
  <p class="sub">Thanks for shopping on PhandaHub. Your purchase has been confirmed.</p>

  <div style="text-align:left;margin:20px 0;">
    <p><strong>Order #:</strong> <?= (int)$order['id'] ?></p>
    <p><strong>Date:</strong> <?= e($order['created_at']) ?></p>
    <p><strong>Status:</strong> <?= e($order['status']) ?></p>
    <p><strong>Total paid:</strong> R <?= number_format($order['total'],2) ?></p>
  </div>

  <div class="table-wrap"><table class="table">
    <tr><th>Item</th><th>Price</th><th>Qty</th></tr>
    <?php while ($it = $items->fetch_assoc()): ?>
      <tr><td><?= e($it['title']) ?></td><td>R <?= number_format($it['price'],2) ?></td><td><?= (int)$it['quantity'] ?></td></tr>
    <?php endwhile; ?>
  </table></div>

  <div style="margin-top:20px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
    <a href="<?= base_url('purchases.php') ?>" class="btn btn-primary">View my purchases</a>
    <a href="<?= base_url('shop.php') ?>" class="btn btn-outline">Continue shopping</a>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
