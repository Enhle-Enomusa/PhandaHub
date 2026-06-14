<?php
// profile.php - profile, recent listings, recent purchases, wallet
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Profile';
$uid = (int)$_SESSION['user_id'];

// Top up wallet (simple demo action).
if (isset($_POST['topup'])) {
    $amt = (float)$_POST['topup'];
    if ($amt > 0 && $amt <= 5000) {
        $stmt = $conn->prepare("UPDATE users SET wallet = wallet + ? WHERE id = ?");
        $stmt->bind_param('di', $amt, $uid); $stmt->execute();
        set_flash('success', "Added R " . number_format($amt,2) . " to your wallet.");
        header('Location: ' . base_url('profile.php')); exit;
    }
}

$user      = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$listings  = $conn->query("SELECT * FROM products WHERE user_id=$uid ORDER BY created_at DESC LIMIT 3");
$purchases = $conn->query("SELECT * FROM orders   WHERE user_id=$uid ORDER BY created_at DESC LIMIT 3");

include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">My profile</h1>

    <div class="grid grid-2">
      <div class="card">
        <h2 style="color:var(--green-dark);margin-bottom:10px;">Account information</h2>
        <p><strong>Name:</strong> <?= e($user['full_name']) ?></p>
        <p><strong>Email:</strong> <?= e($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= e($user['phone']) ?></p>
        <p><strong>Joined:</strong> <?= e($user['created_at']) ?></p>
      </div>

      <div class="card" style="background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;">
        <h2 style="margin-bottom:10px;">Wallet balance</h2>
        <div style="font-size:36px;font-weight:700;margin-bottom:12px;">R <?= number_format($user['wallet'],2) ?></div>
        <form method="post" style="display:flex;gap:8px;">
          <input type="number" name="topup" min="10" max="5000" step="10" placeholder="Top-up amount" required
                 style="flex:1;padding:8px;border-radius:8px;border:none;">
          <button class="btn btn-gold">Top up</button>
        </form>
      </div>
    </div>

    <h2 class="section-title">Recent listings</h2>
    <div class="grid grid-3">
      <?php if ($listings->num_rows === 0): ?>
        <div class="card empty" style="grid-column:1/-1;">No listings yet. <a href="<?= base_url('sell.php') ?>">List one now</a>.</div>
      <?php else: while ($p = $listings->fetch_assoc()): ?>
        <div class="product">
          <img src="<?= e(base_url($p['image'])) ?>" alt="">
          <div class="product-body">
            <div class="product-title"><?= e($p['title']) ?></div>
            <div class="product-price">R <?= number_format($p['price'],2) ?></div>
          </div>
        </div>
      <?php endwhile; endif; ?>
    </div>

    <h2 class="section-title">Recent purchases</h2>
    <div class="card">
      <?php if ($purchases->num_rows === 0): ?>
        <div class="empty">No purchases yet.</div>
      <?php else: ?>
        <table class="table">
          <tr><th>Order</th><th>Total</th><th>Date</th></tr>
          <?php while ($o = $purchases->fetch_assoc()): ?>
            <tr><td>#<?= (int)$o['id'] ?></td><td>R <?= number_format($o['total'],2) ?></td><td><?= e($o['created_at']) ?></td></tr>
          <?php endwhile; ?>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
