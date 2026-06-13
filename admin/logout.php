<?php
// admin/logout.php
require_once __DIR__ . '/../includes/auth.php';
unset($_SESSION['admin_id'], $_SESSION['admin_user']);
header('Location: ' . base_url('admin/login.php'));
exit;
