<?php
// login.php - user sign in
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
$page_title = 'Sign In';
$errors = [];

if (!empty($_GET['msg']) && $_GET['msg'] === 'login_required') {
    $errors[] = 'Please sign in to continue.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare('SELECT id, full_name, password FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            set_flash('success', 'Welcome back, ' . $user['full_name'] . '!');
            header('Location: ' . base_url('dashboard.php'));
            exit;
        }
        $errors[] = 'Invalid email or password.';
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card form-card">
  <h1>Sign in</h1>
  <p class="sub">Welcome back to PhandaHub.</p>

  <?php foreach ($errors as $err): ?>
    <div class="form-error"><?= e($err) ?></div>
  <?php endforeach; ?>

  <form id="loginForm" method="post" novalidate>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
    <p class="form-foot">New here? <a href="<?= base_url('register.php') ?>">Create an account</a></p>
    <p class="form-foot" style="font-size:12px;">Demo: <code>thabo@demo.com</code> / <code>password123</code></p>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
