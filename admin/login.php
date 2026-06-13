<?php
// admin/login.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
$page_title = 'Admin Login';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u === '' || $p === '') {
        $errors[] = 'Username and password are required.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param('s', $u); $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        if ($admin && password_verify($p, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            header('Location: ' . base_url('admin/dashboard.php')); exit;
        }
        $errors[] = 'Invalid admin credentials.';
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="card form-card">
  <h1>Admin sign in</h1>
  <p class="sub">Administrators only.</p>
  <?php foreach ($errors as $err): ?><div class="form-error"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" novalidate>
    <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
    <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
    <button class="btn btn-primary btn-block">Sign in as admin</button>
    <p class="form-foot" style="font-size:12px;">Demo: <code>admin</code> / <code>admin123</code></p>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
