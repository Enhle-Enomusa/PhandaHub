<?php
// admin/users.php - list and delete users
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_admin();
$page_title = 'Manage Users';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $d = $conn->prepare("DELETE FROM users WHERE id=?");
    $d->bind_param('i', $id); $d->execute();
    set_flash('success', 'User deleted.');
    header('Location: ' . base_url('admin/users.php')); exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
include __DIR__ . '/../includes/header.php';
?>
<div class="layout">
  <?php include __DIR__ . '/../includes/admin_sidebar.php'; ?>
  <div>
    <h1 style="color:var(--green-dark);margin-bottom:16px;">Users (<?= $users->num_rows ?>)</h1>
    <div class="table-wrap"><table class="table">
      <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Wallet</th><th>Joined</th><th></th></tr>
      <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= e($u['full_name']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['phone']) ?></td>
          <td>R <?= number_format($u['wallet'],2) ?></td>
          <td><?= e($u['created_at']) ?></td>
          <td><a href="?delete=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this user and all their data?')">Delete</a></td>
        </tr>
      <?php endwhile; ?>
    </table></div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
