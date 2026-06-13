<?php
// index.php - Landing page
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$page_title = 'Home';

// Pull a few featured products to showcase on the landing page.
$featured = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 3");

include __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div>
    <h1>Buy &amp; sell with your community.</h1>
    <p>PhandaHub is South Africa's friendly C2C marketplace. List the things you no longer need, discover great deals from real people near you, and trade with confidence.</p>
    <div class="hero-actions">
      <a href="<?= base_url('register.php') ?>" class="btn btn-gold">Register</a>
      <a href="<?= base_url('login.php') ?>" class="btn btn-outline" style="background:#fff;">Sign In</a>
      <a href="<?= base_url('shop.php') ?>" class="btn btn-primary" style="background:#fff;color:#007749;">Browse Shop</a>
    </div>
  </div>
  <img src="<?= base_url('images/logo.png') ?>" alt="PhandaHub Marketplace">
</section>

<section class="about" id="about">
  <h2>About PhandaHub</h2>
  <p>PhandaHub Marketplace empowers everyday South Africans to buy and sell directly with one another. Whether you're cleaning out the garage or hunting for a bargain, our secure platform makes person-to-person trading simple, safe and local.</p>
</section>

<h2 class="section-title">Latest Listings</h2>
<div class="grid grid-3">
<?php while ($p = $featured->fetch_assoc()): ?>
  <div class="product">
    <img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>">
    <div class="product-body">
      <div class="product-title"><?= e($p['title']) ?></div>
      <div class="product-price">R <?= number_format($p['price'], 2) ?></div>
      <div class="product-meta"><?= e($p['category']) ?></div>
      <div class="product-actions">
        <a href="<?= base_url('product.php?id=' . $p['id']) ?>" class="btn btn-primary btn-block">View</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
