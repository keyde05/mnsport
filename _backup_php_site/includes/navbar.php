<?php
$p = $page ?? '';
function act($check, $cur) { return $check === $cur ? ' class="active"' : ''; }
function mob_act($check, $cur) { return 'mob-lnk' . ($check === $cur ? ' active' : ''); }
$prodPages = ['products','football','casual','formal'];
$prodActive = in_array($p, $prodPages) ? ' class="active"' : '';
?>
  <!-- CURSOR -->
  <div id="cDot"></div>
  <div id="cRing"></div>

  <!-- NAVBAR -->
  <nav id="navbar">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">
        <img src="logo/logo.jpg" alt="MN Sports" class="logo-img">
        <span class="logo-txt">MN<span>sport</span></span>
      </a>
      <ul class="nav-links" id="navLinks">
        <li><a href="index.php"<?= act('home', $p) ?>>Home</a></li>
        <li class="has-drop">
          <a href="products.php"<?= $prodActive ?>>Produk <span class="drop-arrow">▾</span></a>
          <div class="nav-drop">
            <a href="football.php"<?= act('football', $p) ?>>⚽ Football</a>
            <a href="casual.php"<?= act('casual', $p) ?>>👕 Casual</a>
            <a href="formal.php"<?= act('formal', $p) ?>>👔 Formal</a>
            <div class="drop-divider"></div>
            <a href="products.php"<?= act('products', $p) ?>>Semua Koleksi</a>
          </div>
        </li>
        <li><a href="order.php"<?= act('order', $p) ?>>Cara Order</a></li>
        <li><a href="about.php"<?= act('about', $p) ?>>Tentang Kami</a></li>
        <li><a href="contact.php"<?= act('contact', $p) ?>>Hubungi</a></li>
      </ul>
      <div class="nav-end">
        <button class="cart-btn" id="cartBtn" aria-label="Buka Troli">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="cart-count" id="cartCount">0</span>
        </button>
        <a href="order.php" class="btn-nav">Order Sekarang</a>
        <button class="burger" id="burger" aria-label="Menu">
          <svg class="burger-svg" width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect class="bl1" x="0" y="0"   width="28" height="3" rx="1.5" fill="white"/>
            <rect class="bl2" x="0" y="8.5" width="28" height="3" rx="1.5" fill="white"/>
            <rect class="bl3" x="0" y="17"  width="28" height="3" rx="1.5" fill="white"/>
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <!-- MOBILE MENU -->
  <div class="mob-menu" id="mobMenu">
    <ul>
      <li><a href="index.php"    class="<?= mob_act('home',     $p) ?>">Home</a></li>
      <li class="mob-cat-hd">Produk</li>
      <li><a href="football.php" class="<?= mob_act('football', $p) ?>">⚽ Football</a></li>
      <li><a href="casual.php"   class="<?= mob_act('casual',   $p) ?>">👕 Casual</a></li>
      <li><a href="formal.php"   class="<?= mob_act('formal',   $p) ?>">👔 Formal</a></li>
      <li><a href="products.php" class="<?= mob_act('products', $p) ?>">Semua Koleksi</a></li>
      <li><a href="order.php"    class="<?= mob_act('order',    $p) ?>">Cara Order</a></li>
      <li><a href="about.php"    class="<?= mob_act('about',    $p) ?>">Tentang Kami</a></li>
      <li><a href="contact.php"  class="<?= mob_act('contact',  $p) ?>">Hubungi</a></li>
      <li><a href="order.php" class="mob-lnk mob-cta">Order Sekarang</a></li>
    </ul>
  </div>
