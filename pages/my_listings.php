<?php
// my_listings.php - user's own listings with edit/delete
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'My Listings';
$uid = (int)$_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $pid = (int)$_GET['delete'];
    $d = $conn->prepare("DELETE FROM products WHERE id=? AND user_id=?");
    $d->bind_param('ii', $pid, $uid); $d->execute();
    set_flash('success', 'Listing deleted.');
    header('Location: ' . base_url('my_listings.php')); exit;
}

$items = $conn->query("SELECT * FROM products WHERE user_id=$uid ORDER BY created_at DESC");
include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
      <h1 style="color:var(--green-dark);">My listings</h1>
      <a href="<?= base_url('sell.php') ?>" class="btn btn-primary">+ List New Item</a>
    </div>

    <?php if ($items->num_rows === 0): ?>
      <div class="empty card">You haven't listed anything yet.</div>
    <?php else: ?>
      <div class="grid grid-3">
        <?php while ($p = $items->fetch_assoc()): ?>
          <div class="product">
            <img src="<?= e(base_url($p['image'])) ?>" alt="">
            <div class="product-body">
              <div class="product-title"><?= e($p['title']) ?></div>
              <div class="product-price">R <?= number_format($p['price'],2) ?></div>
              <div class="product-meta"><?= e($p['category']) ?> &middot; Stock: <?= (int)$p['stock'] ?></div>
              <div class="product-actions">
                <a href="<?= base_url('product.php?id='.$p['id']) ?>" class="btn btn-outline" style="flex:1;">View</a>
                <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this listing?')">Delete</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
