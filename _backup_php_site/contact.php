<?php
$page  = 'contact';
$title = 'Hubungi Kami — MN Sports';
$desc  = 'Hubungi MN Sports — WhatsApp, Email & Instagram. Kami sedia membantu 24/7.';
include 'includes/head.php';
?>
<body>

<?php include 'includes/navbar.php'; ?>

  <main>

    <!-- PAGE HEADER -->
    <div class="page-hd">
      <div class="container">
        <span class="s-tag">Kami Sedia Membantu</span>
        <h1>HUBUNGI KAMI</h1>
        <p>Hubungi melalui mana-mana saluran di bawah. Kami akan membalas dalam masa 1 jam.</p>
      </div>
    </div>

    <!-- CONTACT -->
    <section id="contact" class="s-contact">
      <div class="container">
        <div class="contact-cards">
          <div class="cc rv dl1">
            <div class="cc-ico wa">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </div>
            <h3>WhatsApp</h3>
            <p>Chat terus untuk maklumbalas segera.</p>
            <a href="<?= WA_URL ?>" target="_blank" rel="noopener" class="cc-lnk"><?= WA_DISPLAY ?></a>
          </div>
          <div class="cc rv dl2">
            <div class="cc-ico em">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <h3>Email</h3>
            <p>Hantar pertanyaan atau design file.</p>
            <a href="mailto:<?= SITE_EMAIL ?>" class="cc-lnk"><?= SITE_EMAIL ?></a>
          </div>
          <div class="cc rv dl3">
            <div class="cc-ico ig">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </div>
            <h3>Instagram</h3>
            <p>Ikuti kami untuk design & update terkini.</p>
            <a href="<?= SITE_IG ?>" target="_blank" rel="noopener" class="cc-lnk">@mnsports</a>
          </div>
        </div>

        <!-- FAQ -->
        <div class="s-hd rv" style="margin-top:5rem">
          <span class="s-tag">FAQ</span>
          <h2 class="s-title">SOALAN LAZIM</h2>
        </div>
        <div class="faq-grid rv">
          <div class="faq-item"><h4>Berapa minimum order?</h4><p>Minimum 10 helai bagi setiap order. Tiada had maksimum.</p></div>
          <div class="faq-item"><h4>Berapa lama siap?</h4><p>7–14 hari bekerja selepas deposit diterima dan mockup diluluskan.</p></div>
          <div class="faq-item"><h4>Boleh buat design sendiri?</h4><p>Ya! Hantar rekaan anda atau bincang dengan kami untuk design eksklusif.</p></div>
          <div class="faq-item"><h4>Penghantaran ke seluruh Malaysia?</h4><p>Ya, kami hantar ke seluruh Semenanjung, Sabah & Sarawak.</p></div>
          <div class="faq-item"><h4>Boleh tambah nama & nombor?</h4><p>Boleh! Sublimasi penuh bermaksud nama, nombor dan logo boleh dicetak terus pada baju.</p></div>
          <div class="faq-item"><h4>Berapa deposit yang perlu dibayar?</h4><p>Deposit 50% diperlukan sebelum proses pengeluaran bermula. Baki 50% sebelum penghantaran.</p></div>
        </div>
      </div>
    </section>

    <!-- CTA BANNER -->
    <section class="s-banner">
      <div class="banner-inner rv">
        <span class="s-tag light">Masih Ada Soalan?</span>
        <h2>CHAT DENGAN<br>KAMI SEKARANG.</h2>
        <p>Pasukan kami sedia menjawab sebarang pertanyaan anda melalui WhatsApp.</p>
        <a href="<?= WA_URL ?>" class="btn-primary" target="_blank" rel="noopener">WhatsApp Sekarang</a>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
