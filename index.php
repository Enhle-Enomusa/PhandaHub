<?php
// index.php - Landing page

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$page_title = 'Home';

// Pull a few featured products to showcase on the landing page
$featured = null;

if (isset($conn) && $conn instanceof mysqli) {
    $featured = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 3");
}
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div>
    <h1>Buy &amp; sell with your community.</h1>

    <p>
      PhandaHub is South Africa's friendly C2C marketplace.
      List the things you no longer need, discover great deals from real people near you,
      and trade with confidence.
    </p>

 <div class="hero-actions">
    <a href="/pages/register.php" class="btn btn-gold">Register</a>
    <a href="/pages/login.php" class="btn btn-outline">Sign In</a>
    <a href="/pages/shop.php" class="btn btn-primary">Browse Shop</a>
</div>

 <img src="/images/logo.png?v=2" alt="PhandaHub Marketplace Logo">
</section>

<section class="about" id="about">
  <h2>About PhandaHub</h2>

  <p>
    PhandaHub Marketplace empowers everyday South Africans to buy and sell directly
    with one another. Whether you're cleaning out the garage or hunting for a bargain,
    our secure platform makes person-to-person trading simple, safe and local.
  </p>
</section>

<h2 class="section-title">Latest Listings</h2>

<div class="grid grid-3">

<?php if ($featured && $featured->num_rows > 0): ?>

  <?php while ($p = $featured->fetch_assoc()): ?>

    <?php
      // If image is empty, use a default image
      $image = !empty($p['image']) ? $p['image'] : 'images/default-product.png';

      // If image path does not already start with http or /, make it relative to project root
      if (!preg_match('/^(http|\/)/', $image)) {
          $image = base_url($image);
      }
    ?>

    <div class="product">
      <img src="<?= e($image) ?>" alt="<?= e($p['title']) ?>">

      <div class="product-body">
        <div class="product-title"><?= e($p['title']) ?></div>

        <div class="product-price">
          R <?= number_format((float)$p['price'], 2) ?>
        </div>

        <div class="product-meta">
          <?= e($p['category']) ?>
        </div>

        <div class="product-actions">
         <a href="/pages/product.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-block">
            View
          </a>
        </div>
      </div>
    </div>

  <?php endwhile; ?>

<?php else: ?>

  <p>No products have been listed yet.</p>

<?php endif; ?>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>