<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($pageTitle ?? 'Admin') ?> — MN Sports Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $cssPath ?? 'assets/admin.css' ?>">
  <link rel="icon" href="../logo/logo.jpg" type="image/jpeg">
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sb-logo">
    <img src="../logo/logo.jpg" alt="MN Sports">
    <div>
      <div class="sb-logo-txt">MN<span>SPORTS</span></div>
      <div class="sb-logo-sub">Admin Panel</div>
    </div>
  </div>

  <nav class="sb-nav">
    <div class="sb-section">Utama</div>
    <a href="dashboard.php" class="sb-link <?= ($currentPage==='dashboard')?'active':'' ?>">
      <span class="sb-icon">📊</span> Dashboard
    </a>

    <div class="sb-section">Katalog</div>
    <a href="products.php" class="sb-link <?= ($currentPage==='products')?'active':'' ?>">
      <span class="sb-icon">👕</span> Produk
    </a>
    <a href="categories.php" class="sb-link <?= ($currentPage==='categories')?'active':'' ?>">
      <span class="sb-icon">📂</span> Kategori
    </a>

    <div class="sb-section">Pesanan</div>
    <a href="orders.php" class="sb-link <?= ($currentPage==='orders')?'active':'' ?>">
      <span class="sb-icon">📋</span> Pesanan Masuk
    </a>

    <div class="sb-section">Sistem</div>
    <a href="settings.php" class="sb-link <?= ($currentPage==='settings')?'active':'' ?>">
      <span class="sb-icon">⚙️</span> Tetapan
    </a>
    <?php if (is_superadmin()): ?>
    <a href="users.php" class="sb-link <?= ($currentPage==='users')?'active':'' ?>">
      <span class="sb-icon">👥</span> Pengguna
    </a>
    <?php endif; ?>
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <div class="sb-avatar"><?= strtoupper(substr($adminUser['username'],0,1)) ?></div>
      <div>
        <div class="sb-uname"><?= h($adminUser['username']) ?></div>
        <div class="sb-urole"><?= h($adminUser['role']) ?></div>
      </div>
    </div>
    <a href="logout.php" class="sb-logout">⏻ Log Keluar</a>
  </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="main">
  <header class="topbar">
    <div>
      <div class="topbar-title"><?= h($pageTitle ?? '') ?></div>
      <?php if (!empty($breadcrumb)): ?>
      <div class="topbar-breadcrumb"><?= $breadcrumb ?></div>
      <?php endif; ?>
    </div>
    <div class="topbar-right">
      <span class="topbar-badge"><?= h($adminUser['role']) ?></span>
      <a href="../index.php" target="_blank" style="font-size:.75rem;color:var(--muted)">Lihat Laman ↗</a>
    </div>
  </header>
  <div class="content">
