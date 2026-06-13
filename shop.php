<?php
// shop.php - browse all listings
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$page_title = 'Shop';

$search = trim($_GET['q'] ?? '');
$sql = "SELECT p.*, u.full_name AS seller FROM products p JOIN users u ON u.id = p.user_id WHERE p.stock > 0";
if ($search !== '') {
    $stmt = $conn->prepare($sql . " AND (p.title LIKE ? OR p.category LIKE ?) ORDER BY p.created_at DESC");
    $like = "%$search%"; $stmt->bind_param('ss', $like, $like);
    $stmt->execute(); $items = $stmt->get_result();
} else {
    $items = $conn->query($sql . " ORDER BY p.created_at DESC");
}

include __DIR__ . '/includes/header.php';
?>
<h1 style="color:var(--green-dark);margin-bottom:10px;">Shop the marketplace</h1>
<p style="color:var(--muted);margin-bottom:18px;">All items listed by PhandaHub members.</p>

<form method="get" style="margin-bottom:20px;display:flex;gap:10px;max-width:480px;">
  <input type="text" name="q" placeholder="Search products or categories..." value="<?= e($search) ?>" style="flex:1;padding:10px;border:1px solid var(--border);border-radius:8px;">
  <button class="btn btn-primary">Search</button>
</form>

<?php if ($items->num_rows === 0): ?>
  <div class="empty card">No products match your search. <a href="<?= base_url('shop.php') ?>">Show all</a>.</div>
<?php else: ?>
  <div class="grid grid-4">
    <?php while ($p = $items->fetch_assoc()): ?>
      <div class="product">
        <img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>">
        <div class="product-body">
          <div class="product-title"><?= e($p['title']) ?></div>
          <div class="product-price">R <?= number_format($p['price'], 2) ?></div>
          <div class="product-meta"><?= e($p['category']) ?> &middot; by <?= e($p['seller']) ?></div>
          <div class="product-actions">
            <a href="<?= base_url('product.php?id=' . $p['id']) ?>" class="btn btn-outline" style="flex:1;">View</a>
            <a href="<?= base_url('cart.php?add=' . $p['id']) ?>" class="btn btn-primary" style="flex:1;">Buy</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
