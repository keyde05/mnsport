<?php
$page  = 'casual';
$title = 'Koleksi Casual — MN Sports';
$desc  = 'Polo & jersey santai sublimasi premium Malaysia. Sesuai untuk program, team building & komuniti.';
include 'includes/head.php';
?>
<body class="cat-casual">

<?php include 'includes/navbar.php'; ?>

  <main>

    <div class="page-hd">
      <div class="container">
        <span class="s-tag">👕 Koleksi Casual</span>
        <h1>BAJU CASUAL<br>UNTUK SEMUA</h1>
        <p>Polo & jersey santai dengan rekaan sublimasi eksklusif. Sesuai untuk team building, program komuniti, dan majlis tidak rasmi.</p>
      </div>
    </div>

    <!-- CASUAL HIGHLIGHTS -->
    <div class="cat-highlights">
      <div class="container">
        <div class="cat-hl-grid">
          <div class="cat-hl rv dl1">
            <div class="cat-hl-ico">🎨</div>
            <div>
              <h4>Warna Bebas</h4>
              <p>Pilih mana-mana kombinasi warna — sublimasi penuh tanpa had warna tambahan.</p>
            </div>
          </div>
          <div class="cat-hl rv dl2">
            <div class="cat-hl-ico">👥</div>
            <div>
              <h4>Sesuai Berkumpulan</h4>
              <p>Ideal untuk team building, family day, program jabatan, dan seragam komuniti.</p>
            </div>
          </div>
          <div class="cat-hl rv dl3">
            <div class="cat-hl-ico">✨</div>
            <div>
              <h4>Polo & Round Neck</h4>
              <p>Tersedia dalam pilihan kolar polo dan round neck mengikut citarasa anda.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <section class="s-products">
      <div class="container">
        <div class="s-hd rv">
          <span class="s-tag">Design Terkini</span>
          <h2 class="s-title">KOLEKSI CASUAL</h2>
          <p class="s-sub">3 design casual eksklusif sublimasi penuh — santai tapi tetap bergaya untuk setiap majlis.</p>
        </div>
        <div class="products-grid" id="productsGrid" data-default-cat="casual"></div>
        <div class="view-all-wrap rv" style="margin-top:3rem">
          <a href="products.php" class="btn-ghost">Lihat Semua Koleksi →</a>
        </div>
      </div>
    </section>

    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Custom Casual</span>
        <h2>DESIGN BAJU<br>TEAM ANDA.</h2>
        <p>Tambah nama ahli, logo organisasi, dan warna pilihan sendiri. Minimum 10 helai sahaja.</p>
        <a href="order.php" class="btn-primary">Custom Order Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
