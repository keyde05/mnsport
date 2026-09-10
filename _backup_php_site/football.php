<?php
$page  = 'football';
$title = 'Koleksi Football — MN Sports';
$desc  = 'Jersey bola sepak & sukan sublimasi premium Malaysia. Design eksklusif untuk pasukan anda.';
include 'includes/head.php';
?>
<body class="cat-football">

<?php include 'includes/navbar.php'; ?>

  <main>

    <div class="page-hd">
      <div class="container">
        <span class="s-tag">⚽ Koleksi Football</span>
        <h1>JERSEY BOLA<br>SEPAK PREMIUM</h1>
        <p>Rekaan sublimasi penuh untuk pasukan yang ingin tampil profesional di padang. Nama, nombor & logo boleh dicetak terus.</p>
      </div>
    </div>

    <!-- FOOTBALL HIGHLIGHTS -->
    <div class="cat-highlights">
      <div class="container">
        <div class="cat-hl-grid">
          <div class="cat-hl rv dl1">
            <div class="cat-hl-ico">⚡</div>
            <div>
              <h4>Fabric Dri-Fit</h4>
              <p>Kain ringan & menyerap peluh — sesuai untuk intensiti permainan tinggi di padang.</p>
            </div>
          </div>
          <div class="cat-hl rv dl2">
            <div class="cat-hl-ico">🎯</div>
            <div>
              <h4>Nama & Nombor Custom</h4>
              <p>Cetak nama pemain, nombor baju, dan logo kelab terus pada kain via sublimasi penuh.</p>
            </div>
          </div>
          <div class="cat-hl rv dl3">
            <div class="cat-hl-ico">🏆</div>
            <div>
              <h4>Piawai Liga & Futsal</h4>
              <p>Rekaan mengikut spesifikasi liga tempatan. Sesuai untuk pertandingan rasmi dan latihan.</p>
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
          <h2 class="s-title">KOLEKSI FOOTBALL</h2>
          <p class="s-sub">4 design eksklusif sublimasi penuh — setiap satu boleh dikustomisasi sepenuhnya untuk pasukan anda.</p>
        </div>
        <div class="products-grid" id="productsGrid" data-default-cat="football"></div>
        <div class="view-all-wrap rv" style="margin-top:3rem">
          <a href="products.php" class="btn-ghost">Lihat Semua Koleksi →</a>
        </div>
      </div>
    </section>

    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Custom Jersey</span>
        <h2>DESIGN JERSEY<br>PASUKAN ANDA.</h2>
        <p>Tambah nama, nombor, dan logo pasukan. Siap dalam 7–14 hari bekerja. Minimum 10 helai sahaja.</p>
        <a href="order.php" class="btn-primary">Custom Order Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
