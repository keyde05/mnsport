<?php
$page  = 'home';
$title = 'MN Sports — Premium Custom Jersey Malaysia';
$desc  = 'MN Sports — Premium Custom Sports Jersey Malaysia. Jersi sublimasi berkualiti untuk Football, Casual & Formal.';

require_once 'includes/db.php';
$catCounts   = ['football' => 0, 'casual' => 0, 'formal' => 0];
$totalActive = 0;
$minOrder    = 10;
if (!empty($GLOBALS['mns_db'])) {
    $rows = $GLOBALS['mns_db']->query(
        "SELECT c.slug, COUNT(p.id) AS cnt
         FROM mn_categories c
         LEFT JOIN mn_products p ON p.category_id = c.id AND p.active = 1
         WHERE c.active = 1 GROUP BY c.slug"
    );
    if ($rows) {
        foreach ($rows->fetchAll() as $r) {
            $catCounts[$r['slug']] = (int)$r['cnt'];
            $totalActive += (int)$r['cnt'];
        }
    }
    $mo = $GLOBALS['mns_db']->query("SELECT value FROM mn_settings WHERE `key`='min_order'")->fetchColumn();
    if ($mo) $minOrder = (int)$mo;
}

include 'includes/head.php';
?>
<body class="is-loading">

  <!-- PRELOADER -->
  <div id="preloader">
    <div class="pl-wrap">
      <img src="logo/logo.jpg" alt="MN Sports" class="pl-logo-img">
      <div class="pl-logo">MN<span>SPORTS</span></div>
      <div class="pl-track"><div class="pl-fill" id="plFill"></div></div>
      <div class="pl-pct" id="plPct">0%</div>
    </div>
  </div>

<?php include 'includes/navbar.php'; ?>

  <main>

    <!-- HERO -->
    <section id="home" class="hero">
      <canvas id="heroCanvas"></canvas>
      <div class="hero-content">
        <div class="hero-tag rv">✦ Premium Custom Sports Apparel Malaysia</div>
        <h1 class="hero-h1">
          <span class="hl accent rv dl1">MN</span>
          <span class="hl rv dl2">SPORT</span>
        </h1>
        <p class="hero-sub rv dl4">Jersi sublimasi berkualiti premium. Football · Casual · Formal.</p>
        <div class="hero-cta rv dl5">
          <a href="products.php" class="btn-primary">Lihat Koleksi</a>
          <a href="order.php" class="btn-ghost">Custom Order <span>→</span></a>
        </div>
        <div class="hero-stats rv dl6">
          <div class="hs">
            <div class="hs-n"><span class="ctr" data-t="500">0</span>+</div>
            <div class="hs-l">Designs</div>
          </div>
          <div class="hs-div"></div>
          <div class="hs">
            <div class="hs-n"><span class="ctr" data-t="1000">0</span>+</div>
            <div class="hs-l">Pelanggan Puas Hati</div>
          </div>
          <div class="hs-div"></div>
          <div class="hs">
            <div class="hs-n"><span class="ctr" data-t="5">0</span>+</div>
            <div class="hs-l">Tahun Pengalaman</div>
          </div>
        </div>
      </div>
      <div class="hero-scroll">
        <div class="scroll-mouse"><div class="scroll-wheel"></div></div>
        <span>Scroll</span>
      </div>
    </section>

    <!-- MARQUEE BAND -->
    <div class="marquee-band" aria-hidden="true">
      <div class="mq-track">
        <span>FOOTBALL JERSEY</span><span class="mx">✦</span>
        <span>CASUAL WEAR</span><span class="mx">✦</span>
        <span>FORMAL UNIFORM</span><span class="mx">✦</span>
        <span>SUBLIMASI PENUH</span><span class="mx">✦</span>
        <span>KUALITI PREMIUM</span><span class="mx">✦</span>
        <span>FOOTBALL JERSEY</span><span class="mx">✦</span>
        <span>CASUAL WEAR</span><span class="mx">✦</span>
        <span>FORMAL UNIFORM</span><span class="mx">✦</span>
        <span>SUBLIMASI PENUH</span><span class="mx">✦</span>
        <span>KUALITI PREMIUM</span><span class="mx">✦</span>
      </div>
    </div>

    <!-- CATEGORIES STRIP -->
    <section class="s-cats">
      <div class="container">
        <div class="cats-grid">
          <a href="football.php" class="cat-card rv dl1">
            <div class="cat-icon">⚽</div>
            <h3>Football</h3>
            <p>Jersey sukan, bola sepak & netball</p>
            <span class="cat-count"><?= $catCounts['football'] ?> Design</span>
          </a>
          <a href="casual.php" class="cat-card rv dl2">
            <div class="cat-icon">👕</div>
            <h3>Casual</h3>
            <p>Polo & jersey santai untuk semua majlis</p>
            <span class="cat-count"><?= $catCounts['casual'] ?> Design</span>
          </a>
          <a href="formal.php" class="cat-card rv dl3">
            <div class="cat-icon">👔</div>
            <h3>Formal</h3>
            <p>Uniform korporat & jabatan rasmi</p>
            <span class="cat-count"><?= $catCounts['formal'] ?> Design</span>
          </a>
        </div>
      </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="s-products s-featured">
      <div class="container">
        <div class="s-hd rv">
          <span class="s-tag">Pilihan Terbaik</span>
          <h2 class="s-title">KOLEKSI PILIHAN</h2>
          <p class="s-sub">Design-design paling popular dari koleksi kami. Minimum order <?= $minOrder ?> helai sahaja.</p>
        </div>
        <div class="products-grid" id="featuredGrid"></div>
        <div class="view-all-wrap rv">
          <a href="products.php" class="btn-primary">Lihat Semua <?= $totalActive ?: 10 ?> Koleksi →</a>
        </div>
      </div>
    </section>

    <!-- SUBLIMASI BANNER -->
    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Teknologi Kami</span>
        <h2>SUBLIMASI PENUH.<br>WARNA KEKAL.<br>KUALITI DIJAMIN.</h2>
        <p>Teknologi percetakan sublimasi memastikan warna yang cerah, tahan lama, dan tidak luntur walaupun selepas banyak kali dibasuh.</p>
        <a href="order.php" class="btn-primary">Design Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
