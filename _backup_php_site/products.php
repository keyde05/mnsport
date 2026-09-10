<?php
$page  = 'products';
$title = 'Produk — MN Sports';
$desc  = 'Koleksi Jersi MN Sports — Football, Casual & Formal. 10 design premium sublimasi.';
include 'includes/head.php';
?>
<body>

<?php include 'includes/navbar.php'; ?>

  <main>

    <!-- PAGE HEADER -->
    <div class="page-hd">
      <div class="container">
        <span class="s-tag">Koleksi Kami</span>
        <h1>SEMUA KOLEKSI</h1>
        <p>10 design premium sublimasi — Football, Casual & Formal. Setiap design boleh dikustomisasi sepenuhnya.</p>
      </div>
    </div>

    <!-- PRODUCTS SECTION -->
    <section class="s-products">
      <div class="container">
        <div class="filter-row rv">
          <button class="f-btn active" data-filter="all">Semua</button>
          <button class="f-btn" data-filter="football">⚽ Football</button>
          <button class="f-btn" data-filter="casual">👕 Casual</button>
          <button class="f-btn" data-filter="formal">👔 Formal</button>
        </div>
        <div class="products-grid" id="productsGrid"></div>
      </div>
    </section>

    <!-- CTA BANNER -->
    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Tak Jumpa Design?</span>
        <h2>BUAT DESIGN<br>SENDIRI.</h2>
        <p>Hantar rekaan anda sendiri atau bincang dengan kami untuk design eksklusif pasukan anda.</p>
        <a href="order.php" class="btn-primary">Custom Order Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
