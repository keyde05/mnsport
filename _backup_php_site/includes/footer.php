  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="fg-brand">
          <div class="footer-logo-wrap">
            <img src="logo/logo.jpg" alt="MN Sports" class="logo-img">
            <div class="nav-logo fg-logo">MN<span>sport</span></div>
          </div>
          <p>Premium Custom Sports Jersey Malaysia. Football · Casual · Formal — jersi sublimasi berkualiti untuk semua.</p>
          <div class="socials">
            <a href="<?= defined('SITE_FB') ? SITE_FB : '#' ?>" aria-label="Facebook" target="_blank"><svg viewBox="0 0 24 24" fill="currentColor" width="20"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></a>
            <a href="<?= SITE_IG ?>" aria-label="Instagram" target="_blank"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
            <a href="<?= defined('SITE_TT') ? SITE_TT : '#' ?>" aria-label="TikTok" target="_blank"><svg viewBox="0 0 24 24" fill="currentColor" width="20"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V9.06a8.23 8.23 0 004.84 1.55V7.17a4.85 4.85 0 01-1.07-.48z"/></svg></a>
            <a href="<?= WA_URL ?>" aria-label="WhatsApp" target="_blank"><svg viewBox="0 0 24 24" fill="currentColor" width="20"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
          </div>
        </div>
        <div class="fg-col">
          <h4>Produk</h4>
          <ul>
            <li><a href="football.php">Jersey Football</a></li>
            <li><a href="casual.php">Jersey Casual</a></li>
            <li><a href="formal.php">Jersey Formal</a></li>
            <li><a href="order.php">Custom Order</a></li>
          </ul>
        </div>
        <div class="fg-col">
          <h4>Syarikat</h4>
          <ul>
            <li><a href="about.php">Tentang Kami</a></li>
            <li><a href="order.php">Cara Order</a></li>
            <li><a href="about.php#testimonials">Ulasan Pelanggan</a></li>
            <li><a href="contact.php">Hubungi Kami</a></li>
          </ul>
        </div>
        <div class="fg-col">
          <h4>Hubungi</h4>
          <ul>
            <li>📱 <?= WA_DISPLAY ?></li>
            <li>📧 <?= SITE_EMAIL ?></li>
            <li>📍 Malaysia</li>
            <li>⏰ Isnin–Sabtu, 9am–6pm</li>
          </ul>
        </div>
      </div>
      <div class="footer-btm">
        <p>© <?= date('Y') ?> MN Sports. Hak Cipta Terpelihara.</p>
        <p>Dibina dengan ❤️ untuk pasukan terbaik Malaysia.</p>
      </div>
    </div>
  </footer>
