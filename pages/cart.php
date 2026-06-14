<?php
// cart.php - shopping cart
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Cart';
$uid = (int)$_SESSION['user_id'];

// Add an item to the cart (idempotent - increments qty).
if (isset($_GET['add'])) {
    $pid = (int)$_GET['add'];
    $check = $conn->prepare("SELECT id FROM products WHERE id = ? AND user_id <> ?");
    $check->bind_param('ii', $pid, $uid); $check->execute();
    if ($check->get_result()->num_rows === 0) {
        set_flash('error', 'You cannot buy your own listing or this product does not exist.');
    } else {
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id=? AND product_id=?");
        $stmt->bind_param('ii', $uid, $pid); $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();
        if ($existing) {
            $q = $existing['quantity'] + 1;
            $u = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
            $u->bind_param('ii', $q, $existing['id']); $u->execute();
        } else {
            $i = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
            $i->bind_param('ii', $uid, $pid); $i->execute();
        }
        set_flash('success', 'Added to cart.');
    }
    header('Location: ' . base_url('cart.php')); exit;
}

// Remove from cart.
if (isset($_GET['remove'])) {
    $cid = (int)$_GET['remove'];
    $d = $conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $d->bind_param('ii', $cid, $uid); $d->execute();
    header('Location: ' . base_url('cart.php')); exit;
}

$items = $conn->query("SELECT c.id, c.quantity, p.id AS pid, p.title, p.price, p.image
                       FROM cart c JOIN products p ON p.id=c.product_id
                       WHERE c.user_id=$uid");
$rows = []; $total = 0;
while ($r = $items->fetch_assoc()) { $r['subtotal'] = $r['price'] * $r['quantity']; $total += $r['subtotal']; $rows[] = $r; }

include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">Your cart</h1>

    <?php if (!$rows): ?>
      <div class="empty card">Your cart is empty. <a href="<?= base_url('shop.php') ?>">Browse the shop</a>.</div>
    <?php else: ?>
      <div class="table-wrap"><table class="table">
        <tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td style="display:flex;align-items:center;gap:10px;">
              <img src="<?= e(base_url($r['image'])) ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
              <a href="<?= base_url('product.php?id='.$r['pid']) ?>"><?= e($r['title']) ?></a>
            </td>
            <td>R <?= number_format($r['price'],2) ?></td>
            <td><?= (int)$r['quantity'] ?></td>
            <td>R <?= number_format($r['subtotal'],2) ?></td>
            <td><a href="?remove=<?= $r['id'] ?>" class="btn btn-danger" onclick="return confirm('Remove item?')">Remove</a></td>
          </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" style="text-align:right;font-weight:700;">Total</td><td colspan="2" style="font-weight:700;color:var(--green);">R <?= number_format($total,2) ?></td></tr>
      </table></div>

      <div style="margin-top:20px;text-align:right;">
        <a href="<?= base_url('shop.php') ?>" class="btn btn-outline">Keep shopping</a>
        <a href="<?= base_url('payment.php') ?>" class="btn btn-primary">Proceed to checkout</a>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
