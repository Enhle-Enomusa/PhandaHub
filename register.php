<?php
// register.php - account creation
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$page_title = 'Register';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Server-side validation (defensive: never trust the client).
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm'] ?? '';

    if ($full_name === '')                                $errors[] = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))       $errors[] = 'Please enter a valid email.';
    if (!preg_match('/^[0-9]{10}$/', $phone))             $errors[] = 'Phone must be exactly 10 digits.';
    if (strlen($password) < 6)                            $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                           $errors[] = 'Passwords do not match.';

    if (!$errors) {
        // Make sure the email is not already registered.
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'An account with that email already exists.';
        }
    }

    if (!$errors) {
        // Securely hash the password before storing.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $full_name, $email, $phone, $hash);
        if ($stmt->execute()) {
            set_flash('success', 'Account created! Please sign in.');
            header('Location: ' . base_url('login.php'));
            exit;
        }
        $errors[] = 'Something went wrong, please try again.';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="card form-card">
  <h1>Create your account</h1>
  <p class="sub">Join the PhandaHub community in seconds.</p>

  <?php foreach ($errors as $err): ?>
    <div class="form-error"><?= e($err) ?></div>
  <?php endforeach; ?>

  <form id="registerForm" method="post" novalidate>
    <div class="form-group">
      <label>Full name</label>
      <input type="text" name="full_name" maxlength="100" required value="<?= e($_POST['full_name'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" maxlength="150" required value="<?= e($_POST['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Phone (10 digits)</label>
      <input type="tel" name="phone" data-numeric maxlength="10" required value="<?= e($_POST['phone'] ?? '') ?>">
      <div class="form-hint">Numbers only.</div>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" minlength="6" required>
    </div>
    <div class="form-group">
      <label>Confirm password</label>
      <input type="password" name="confirm" minlength="6" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Create account</button>
    <p class="form-foot">Already have an account? <a href="<?= base_url('login.php') ?>">Sign in</a></p>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
