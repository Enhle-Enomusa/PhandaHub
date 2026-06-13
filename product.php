<?php
// product.php - product detail page
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT p.*, u.full_name AS seller FROM products p JOIN users u ON u.id = p.user_id WHERE p.id = ?");
$stmt->bind_param('i', $id); $stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) { http_response_code(404); $page_title = 'Not found'; include __DIR__ . '/includes/header.php';
    echo '<div class="empty card">Product not found. <a href="' . base_url('shop.php') . '">Back to shop</a></div>';
    include __DIR__ . '/includes/footer.php'; exit; }

$page_title = $product['title'];
include __DIR__ . '/includes/header.php';
?>
<a href="<?= base_url('shop.php') ?>" style="display:inline-block;margin-bottom:14px;">&larr; Back to shop</a>

<div class="product-detail">
  <img src="<?= e($product['image']) ?>" alt="<?= e($product['title']) ?>">
  <div>
    <h1 style="color:var(--green-dark);"><?= e($product['title']) ?></h1>
    <p style="color:var(--muted);margin:6px 0 14px;">Category: <?= e($product['category']) ?> &middot; Seller: <?= e($product['seller']) ?></p>
    <div class="product-price" style="font-size:30px;margin-bottom:12px;">R <?= number_format($product['price'], 2) ?></div>
    <p style="margin-bottom:18px;"><?= nl2br(e($product['description'])) ?></p>
    <p style="color:var(--muted);margin-bottom:18px;">In stock: <?= (int)$product['stock'] ?></p>
    <a href="<?= base_url('cart.php?add=' . $product['id']) ?>" class="btn btn-primary">Add to cart &amp; checkout</a>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
