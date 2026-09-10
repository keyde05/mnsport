<?php
$page  = 'about';
$title = 'Tentang Kami — MN Sports';
$desc  = 'Tentang MN Sports — Pengeluar jersi sublimasi premium Malaysia sejak 2020.';
include 'includes/head.php';
?>
<body>

<?php include 'includes/navbar.php'; ?>

  <main>

    <!-- PAGE HEADER -->
    <div class="page-hd">
      <div class="container">
        <span class="s-tag">Kenali Kami</span>
        <h1>TENTANG KAMI</h1>
        <p>Pengeluar jersi sublimasi premium Malaysia — lebih 5 tahun, lebih 1,000 pelanggan puas hati.</p>
      </div>
    </div>

    <!-- ABOUT -->
    <section id="about" class="s-about">
      <div class="container">
        <div class="about-grid">
          <div class="about-imgs rv-left">
            <div class="ai-main"><img src="baju/ik.jpg" alt="MN Sports Jersey" loading="lazy"></div>
            <div class="ai-sub">
              <img src="baju/106.jpg" alt="MN Sports Jersey" loading="lazy">
              <div class="ai-badge">Est. 2020</div>
            </div>
          </div>
          <div class="about-text rv-right">
            <span class="s-tag">Tentang Kami</span>
            <h2>KAMI ADALAH<br>MN SPORTS</h2>
            <p>MN Sports adalah pengeluar jersi sublimasi premium yang berpusat di Malaysia. Kami pakar dalam menghasilkan jersi berkualiti tinggi dengan rekaan eksklusif untuk setiap pelanggan.</p>
            <p>Dengan pengalaman lebih 5 tahun, kami telah mempercayai lebih 1,000 pelanggan — pasukan sukan, badan korporat, jabatan kerajaan, dan komuniti di seluruh Malaysia.</p>
            <p>Koleksi kami merangkumi tiga kategori: <strong>Football</strong>, <strong>Casual</strong>, dan <strong>Formal</strong>.</p>
            <div class="about-feats">
              <div class="af"><div class="af-ico">🎨</div><div><strong>Rekaan Eksklusif</strong><span>Design unik disesuaikan untuk anda</span></div></div>
              <div class="af"><div class="af-ico">⚡</div><div><strong>Proses Pantas</strong><span>Siap dalam 7–14 hari bekerja</span></div></div>
              <div class="af"><div class="af-ico">🏆</div><div><strong>Kualiti Terjamin</strong><span>Sublimasi penuh, warna tidak luntur</span></div></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="s-testi">
      <div class="container">
        <div class="s-hd rv">
          <span class="s-tag">Ulasan Pelanggan</span>
          <h2 class="s-title">APA KATA MEREKA?</h2>
        </div>
        <div class="testi-wrap">
          <div class="testi-track" id="testiTrack"></div>
          <div class="testi-nav">
            <button class="tn-btn" id="tPrev">←</button>
            <div class="testi-dots" id="testiDots"></div>
            <button class="tn-btn" id="tNext">→</button>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Jom Bergabung</span>
        <h2>SERTAI 1,000+<br>PELANGGAN KAMI.</h2>
        <p>Dapatkan jersi impian pasukan anda hari ini. Minimum 10 helai sahaja.</p>
        <a href="order.php" class="btn-primary">Order Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
