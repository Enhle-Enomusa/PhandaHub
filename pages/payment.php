<?php
// payment.php - fake payment gateway (for academic prototype only)
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_login();
$page_title = 'Secure Payment';
$uid = (int)$_SESSION['user_id'];
$errors = [];

// Calculate the cart total.
$res = $conn->query("SELECT SUM(p.price * c.quantity) total FROM cart c JOIN products p ON p.id=c.product_id WHERE c.user_id=$uid");
$total = (float)($res->fetch_assoc()['total'] ?? 0);

if ($total <= 0) {
    set_flash('error', 'Your cart is empty.');
    header('Location: ' . base_url('cart.php')); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Re-validate payment fields on the server.
    $card = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
    $cvv  = $_POST['cvv'] ?? '';
    $exp  = $_POST['expiry'] ?? '';
    $name = trim($_POST['holder'] ?? '');

    if (!preg_match('/^[0-9]{16}$/', $card)) $errors[] = 'Card number must be 16 digits.';
    if (!preg_match('/^[0-9]{3,4}$/', $cvv)) $errors[] = 'CVV must be 3 or 4 digits.';
    if ($exp === '')   $errors[] = 'Expiry is required.';
    if ($name === '')  $errors[] = 'Card holder name is required.';

    if (!$errors) {
        // Create the order, copy cart items into order_items, then clear cart.
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
            $stmt->bind_param('id', $uid, $total); $stmt->execute();
            $order_id = $conn->insert_id;

            $items = $conn->query("SELECT c.quantity, p.id AS pid, p.title, p.price
                                   FROM cart c JOIN products p ON p.id=c.product_id
                                   WHERE c.user_id=$uid");
            $ins = $conn->prepare("INSERT INTO order_items (order_id, product_id, title, price, quantity) VALUES (?, ?, ?, ?, ?)");
            while ($r = $items->fetch_assoc()) {
                $ins->bind_param('iisdi', $order_id, $r['pid'], $r['title'], $r['price'], $r['quantity']);
                $ins->execute();
                // Decrement product stock.
                $conn->query("UPDATE products SET stock = GREATEST(stock - {$r['quantity']}, 0) WHERE id = {$r['pid']}");
            }
            $conn->query("DELETE FROM cart WHERE user_id=$uid");
            $conn->commit();

            header('Location: ' . base_url('confirmation.php?order=' . $order_id));
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = 'Payment failed: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card payment-card">
  <div class="brand-row">
    <img src="<?= base_url('images/logo.png') ?>" alt="" style="height:40px;">
    <span style="color:var(--muted);font-size:13px;">🔒 Secure checkout (demo)</span>
  </div>

  <h1 style="color:var(--green-dark);">Pay R <?= number_format($total,2) ?></h1>
  <p class="sub">Enter your card details below. This is a simulated payment used for academic purposes only — no real money is moved.</p>

  <div class="visa">
    <div>PHANDAPAY</div>
    <div class="num">•••• •••• •••• ••••</div>
    <div style="display:flex;justify-content:space-between;font-size:12px;">
      <span>CARDHOLDER NAME</span><span>EXP MM/YY</span>
    </div>
  </div>

  <?php foreach ($errors as $err): ?><div class="form-error"><?= e($err) ?></div><?php endforeach; ?>

  <form id="paymentForm" method="post" novalidate>
    <div class="form-group">
      <label>Card holder name</label>
      <input type="text" name="holder" maxlength="60" required>
    </div>
    <div class="form-group">
      <label>Card number</label>
      <input type="text" name="card_number" placeholder="4242 4242 4242 4242" maxlength="19" required>
      <div class="form-hint">Use any 16-digit number for the demo.</div>
    </div>
    <div class="row">
      <div class="form-group">
        <label>Expiry</label>
        <input type="month" name="expiry" required>
      </div>
      <div class="form-group">
        <label>CVV</label>
        <input type="text" name="cvv" data-numeric maxlength="4" required>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Pay R <?= number_format($total,2) ?> now</button>
    <p class="form-foot"><a href="<?= base_url('cart.php') ?>">Cancel and return to cart</a></p>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
