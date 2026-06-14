<?php
// sell.php - create a new product listing
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'List New Item';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = $_POST['price'] ?? '';
    $category    = trim($_POST['category'] ?? '');
    $stock       = (int)($_POST['stock'] ?? 1);
    $image_path  = '';

    if ($title === '')         $errors[] = 'Title is required.';
    if ($description === '')   $errors[] = 'Description is required.';
    if (!is_numeric($price) || $price <= 0) $errors[] = 'Price must be a positive number.';
    if ($category === '')      $errors[] = 'Please choose a category.';
    if ($stock < 1)            $errors[] = 'Stock must be at least 1.';

    // Optional image upload.
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed))            $errors[] = 'Image must be jpg, png, gif or webp.';
        if ($_FILES['image']['size'] > 4*1024*1024) $errors[] = 'Image must be under 4 MB.';

        if (!$errors) {
            $fname = 'p_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dest  = __DIR__ . '/../uploads/' . $fname;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = 'uploads/' . $fname;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        }
    }

    // Fall back to a placeholder image if none uploaded.
    if (!$errors && $image_path === '') {
        $image_path = 'https://picsum.photos/seed/' . urlencode($title) . '/600/400';
    }

    if (!$errors) {
        $uid = (int)$_SESSION['user_id'];
        $stmt = $conn->prepare('INSERT INTO products (user_id, title, description, price, category, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issdssi', $uid, $title, $description, $price, $category, $image_path, $stock);
        if ($stmt->execute()) {
            set_flash('success', 'Listing created successfully!');
            header('Location: ' . base_url('my_listings.php'));
            exit;
        }
        $errors[] = 'Could not save the listing.';
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <div>
    <div class="card form-card wide" style="margin:0;">
      <h1>List a new item</h1>
      <p class="sub">Fill in the details below and your item will go live on the shop.</p>

      <?php foreach ($errors as $err): ?><div class="form-error"><?= e($err) ?></div><?php endforeach; ?>

      <form id="sellForm" method="post" enctype="multipart/form-data" novalidate>
        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" maxlength="150" required value="<?= e($_POST['title'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" rows="4" required><?= e($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="grid grid-2" style="gap:14px;">
          <div class="form-group">
            <label>Price (R)</label>
            <input type="number" name="price" min="1" step="0.01" required value="<?= e($_POST['price'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Stock available</label>
            <input type="number" name="stock" min="1" value="<?= e($_POST['stock'] ?? '1') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label>Category</label>
          <select name="category" required>
            <option value="">-- choose --</option>
            <?php foreach (['Electronics','Fashion','Furniture','Music','Sports','Gaming','Books','Other'] as $c): ?>
              <option <?= (($_POST['category'] ?? '')===$c?'selected':'') ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Product image (optional)</label>
          <input type="file" name="image" accept="image/*">
          <div class="form-hint">If you don't upload one, a placeholder image will be used.</div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Publish listing</button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
