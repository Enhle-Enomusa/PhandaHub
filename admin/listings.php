<?php
// admin/listings.php - view, update, delete all listings
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_admin();
$page_title = 'Manage Listings';
$errors = [];

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $d = $conn->prepare("DELETE FROM products WHERE id=?");
    $d->bind_param('i', $id); $d->execute();
    set_flash('success', 'Listing deleted.');
    header('Location: ' . base_url('admin/listings.php')); exit;
}

// Handle inline edit submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id    = (int)$_POST['update_id'];
    $title = trim($_POST['title']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $cat   = trim($_POST['category']);
    if ($title && $price > 0 && $stock >= 0 && $cat) {
        $u = $conn->prepare("UPDATE products SET title=?, price=?, stock=?, category=? WHERE id=?");
        $u->bind_param('sdisi', $title, $price, $stock, $cat, $id); $u->execute();
        set_flash('success', 'Listing updated.');
    } else { set_flash('error', 'Invalid fields.'); }
    header('Location: ' . base_url('admin/listings.php')); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $s = $conn->prepare("SELECT * FROM products WHERE id=?");
    $s->bind_param('i', $id); $s->execute();
    $edit = $s->get_result()->fetch_assoc();
}

$rows = $conn->query("SELECT p.*, u.full_name AS seller FROM products p JOIN users u ON u.id=p.user_id ORDER BY p.created_at DESC");
include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">All listings (<?= $rows->num_rows ?>)</h1>

    <?php if ($edit): ?>
      <div class="card" style="margin-bottom:20px;">
        <h2 style="color:var(--green-dark);margin-bottom:10px;">Edit listing #<?= (int)$edit['id'] ?></h2>
        <form method="post">
          <input type="hidden" name="update_id" value="<?= (int)$edit['id'] ?>">
          <div class="grid grid-2" style="gap:14px;">
            <div class="form-group"><label>Title</label><input name="title" value="<?= e($edit['title']) ?>" required></div>
            <div class="form-group"><label>Category</label><input name="category" value="<?= e($edit['category']) ?>" required></div>
            <div class="form-group"><label>Price</label><input type="number" step="0.01" name="price" value="<?= e($edit['price']) ?>" required></div>
            <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?= e($edit['stock']) ?>" required></div>
          </div>
          <button class="btn btn-primary">Save</button>
          <a href="<?= base_url('admin/listings.php') ?>" class="btn btn-ghost">Cancel</a>
        </form>
      </div>
    <?php endif; ?>

    <div class="table-wrap"><table class="table">
      <tr><th>ID</th><th>Title</th><th>Seller</th><th>Price</th><th>Stock</th><th>Category</th><th></th></tr>
      <?php while ($p = $rows->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$p['id'] ?></td>
          <td><?= e($p['title']) ?></td>
          <td><?= e($p['seller']) ?></td>
          <td>R <?= number_format($p['price'],2) ?></td>
          <td><?= (int)$p['stock'] ?></td>
          <td><?= e($p['category']) ?></td>
          <td>
            <a href="<?= base_url('product.php?id='.$p['id']) ?>" class="btn btn-ghost">View</a>
            <a href="?edit=<?= $p['id'] ?>" class="btn btn-gold">Edit</a>
            <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete listing?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table></div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
