<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!empty($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare('SELECT * FROM mn_users WHERE (username = ? OR email = ?) AND active = 1');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_role'] = $user['role'];
            $pdo->prepare('UPDATE mn_users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);
            redirect('dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Sila isi semua maklumat.';
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Log Masuk — MN Sports Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/admin.css">
  <link rel="icon" href="../logo/logo.jpg" type="image/jpeg">
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <div class="login-logo">
      <img src="../logo/logo.jpg" alt="MN Sports" style="width:52px;height:52px;border-radius:12px;object-fit:cover;margin:0 auto .75rem">
      <div class="login-logo-txt">MN<span>SPORTS</span></div>
      <div class="login-sub">Admin Panel</div>
    </div>

    <h2>Log Masuk</h2>

    <?php if ($error): ?>
    <div class="flash flash-err"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" style="display:flex;flex-direction:column;gap:.85rem">
      <?= csrf_field() ?>
      <div class="f-grp">
        <label>Username / Email</label>
        <input type="text" name="username" value="<?= h($_POST['username'] ?? '') ?>" placeholder="Masukkan username" required autofocus>
      </div>
      <div class="f-grp">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top:.25rem;justify-content:center;padding:.65rem">
        Log Masuk →
      </button>
    </form>

  </div>
</div>
</body>
</html>
