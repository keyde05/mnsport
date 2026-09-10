<?php
$page  = 'order';
$title = 'Cara Order — MN Sports';
$desc  = 'Cara Order & Custom Order MN Sports — 4 langkah mudah untuk dapatkan jersi impian anda.';
include 'includes/head.php';
?>
<body>

<?php include 'includes/navbar.php'; ?>

  <main>

    <!-- PAGE HEADER -->
    <div class="page-hd">
      <div class="container">
        <span class="s-tag">Mudah &amp; Pantas</span>
        <h1>CARA ORDER</h1>
        <p>4 langkah mudah untuk dapatkan jersi impian pasukan anda dalam masa 7–14 hari bekerja.</p>
      </div>
    </div>

    <!-- PROCESS STEPS -->
    <section id="process" class="s-process">
      <div class="container">
        <div class="steps-grid">
          <div class="step rv dl1">
            <div class="step-num">01</div>
            <div class="step-icon">
              <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="24" cy="24" r="20"/><path d="M24 14v10l6 6"/>
              </svg>
            </div>
            <h3>Pilih Design</h3>
            <p>Pilih dari <a href="products.php" style="color:var(--red)">koleksi kami</a> atau bawa rekaan sendiri untuk disuaikan mengikut cita rasa anda.</p>
          </div>
          <div class="step rv dl2">
            <div class="step-num">02</div>
            <div class="step-icon">
              <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M32 4H10a4 4 0 00-4 4v32a4 4 0 004 4h28a4 4 0 004-4V16L32 4z"/>
                <path d="M32 4v12h12M20 26h8M20 32h8M16 26h1M16 32h1"/>
              </svg>
            </div>
            <h3>Hantar Butiran</h3>
            <p>Berikan senarai nama, saiz, nombor, dan logo pasukan kepada kami melalui WhatsApp atau borang di bawah.</p>
          </div>
          <div class="step rv dl3">
            <div class="step-num">03</div>
            <div class="step-icon">
              <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="4" y="8" width="40" height="28" rx="3"/>
                <path d="M4 18h40M16 36l-4 6M32 36l4 6M18 42h12"/>
              </svg>
            </div>
            <h3>Semak &amp; Bayar</h3>
            <p>Kami hantar mockup untuk kelulusan. Bayaran deposit 50% sebelum pengeluaran dimulakan.</p>
          </div>
          <div class="step rv dl4">
            <div class="step-num">04</div>
            <div class="step-icon">
              <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 24h32M28 12l12 12-12 12"/>
              </svg>
            </div>
            <h3>Terima Jersi</h3>
            <p>Jersi siap dalam 7–14 hari bekerja dan dihantar terus ke pintu rumah anda seluruh Malaysia.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CUSTOM ORDER FORM -->
    <section id="custom-order" class="s-order">
      <div class="container">
        <div class="order-grid">
          <div class="order-info rv-left">
            <span class="s-tag">Buat Tempahan</span>
            <h2>CUSTOM<br>ORDER</h2>
            <p>Isi borang di sebelah dan kami akan menghubungi anda dalam masa 24 jam untuk berbincang lanjut.</p>
            <a href="<?= WA_URL ?>" class="btn-wa" target="_blank" rel="noopener">
              <svg viewBox="0 0 24 24" fill="currentColor" width="22"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              WhatsApp Kami
            </a>
            <ul class="order-perks">
              <li>✓ Mockup design percuma</li>
              <li>✓ Minimum 10 helai sahaja</li>
              <li>✓ Siap dalam 7–14 hari bekerja</li>
              <li>✓ Hantar seluruh Malaysia</li>
              <li>✓ Boleh tambah nama &amp; logo</li>
            </ul>
          </div>

          <div class="order-form-wrap rv-right">
            <form id="orderForm" class="order-form" novalidate>
              <div class="f-row">
                <div class="f-grp">
                  <label>Nama Anda *</label>
                  <input type="text" name="nama" placeholder="Contoh: Ahmad Bin Ali" required>
                </div>
                <div class="f-grp">
                  <label>No. Telefon *</label>
                  <input type="tel" name="telefon" placeholder="+60 12-xxx xxxx" required>
                </div>
              </div>
              <div class="f-grp">
                <label>Nama Pasukan / Organisasi</label>
                <input type="text" name="pasukan" placeholder="Contoh: KKSB FC, Syarikat XYZ Sdn Bhd">
              </div>
              <div class="f-row">
                <div class="f-grp">
                  <label>Kategori</label>
                  <select name="kategori">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Football">⚽ Football</option>
                    <option value="Casual">👕 Casual</option>
                    <option value="Formal">👔 Formal</option>
                  </select>
                </div>
                <div class="f-grp">
                  <label>Jenis Baju</label>
                  <select name="jenis">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Jersey (Round/V-Neck)">Jersey (Round/V-Neck)</option>
                    <option value="Polo Collar">Polo Collar</option>
                  </select>
                </div>
              </div>
              <div class="f-grp">
                <label>Design Pilihan</label>
                <select name="design">
                  <option value="">-- Pilih Design --</option>
                  <optgroup label="⚽ Football">
                    <option>Design #093 – Brush Art Jersey</option>
                    <option>Design #106 N9 – Football Strike</option>
                    <option>Design #108 – Ocean Splash</option>
                    <option>Design HTJS – Teal Marble</option>
                  </optgroup>
                  <optgroup label="👕 Casual">
                    <option>Design #000 – Classic Polo Pro</option>
                    <option>Design #109 – Geometric Fade</option>
                    <option>Design SGMC3 – Speed Racer</option>
                  </optgroup>
                  <optgroup label="👔 Formal">
                    <option>Design #103 – Corporate Marine</option>
                    <option>Design #106 – Royal Baroque</option>
                    <option>Design IK – Heritage Polo</option>
                  </optgroup>
                  <option value="Rekaan Sendiri (Custom)">Rekaan Sendiri (Custom)</option>
                </select>
              </div>
              <div class="f-grp">
                <label>Anggaran Kuantiti <span class="lbl-note">(Minimum 10 helai)</span></label>
                <div class="qty-grid">
                  <div class="qty-sz"><span>XS</span><input type="number" name="xs"   min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>S</span> <input type="number" name="s"    min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>M</span> <input type="number" name="m"    min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>L</span> <input type="number" name="l"    min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>XL</span><input type="number" name="xl"   min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>XXL</span><input type="number" name="xxl" min="0" value="0" class="qin"></div>
                  <div class="qty-sz"><span>3XL</span><input type="number" name="xxxl" min="0" value="0" class="qin"></div>
                </div>
                <div class="qty-total">Jumlah: <strong id="qtyTotal">0</strong> helai</div>
              </div>
              <div class="f-grp">
                <label>Ada nama / nombor pada baju?</label>
                <div class="radio-row">
                  <label class="rl"><input type="radio" name="customName" value="ya"> Ya</label>
                  <label class="rl"><input type="radio" name="customName" value="tidak" checked> Tidak</label>
                </div>
              </div>
              <div class="f-grp">
                <label>Catatan Tambahan</label>
                <textarea name="catatan" rows="3" placeholder="Contoh: tukar warna kepada biru gelap, tambah logo syarikat, dsb."></textarea>
              </div>
              <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24" fill="currentColor" width="20"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Hantar via WhatsApp
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

  </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/bottom.php'; ?>
