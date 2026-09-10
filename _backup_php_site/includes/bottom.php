<?php
// Ambil produk & settings dari DB untuk JS
$_db  = $GLOBALS['mns_db'] ?? null;
$_mns_products = [];
$_mns_waNum    = defined('WA_NUM') ? WA_NUM : '60123456789';

if ($_db) {
    $stmt = $_db->query(
        "SELECT p.id, p.name, p.design, p.image, p.price, p.colors,
                p.description, p.badge, p.badge_class, p.featured,
                COALESCE(c.slug,'') AS category
         FROM mn_products p
         LEFT JOIN mn_categories c ON c.id = p.category_id
         WHERE p.active = 1
         ORDER BY p.sort_order ASC, p.id ASC"
    );
    if ($stmt) {
        foreach ($stmt->fetchAll() as $r) {
            $_mns_products[] = [
                'id'         => 'p' . $r['id'],
                'name'       => $r['name'],
                'design'     => $r['design'],
                'image'      => $r['image'],
                'category'   => $r['category'] ?: 'football',
                'price'      => (float)$r['price'],
                'colors'     => $r['colors'],
                'desc'       => $r['description'],
                'badge'      => $r['badge']       ?: '',
                'badgeClass' => $r['badge_class'] ?: '',
                'featured'   => (bool)$r['featured'],
            ];
        }
    }
    $wa = $_db->query("SELECT value FROM mn_settings WHERE `key`='wa_num'")->fetchColumn();
    if ($wa) $_mns_waNum = $wa;
}
?>

  <!-- CART SIDEBAR -->
  <div class="cart-overlay" id="cartOverlay"></div>
  <aside class="cart-sidebar" id="cartSidebar">
    <div class="cs-hd">
      <h3>Troli Saya</h3>
      <button class="cs-close" id="csClose">✕</button>
    </div>
    <div class="cs-body" id="csBody">
      <div class="cs-empty" id="csEmpty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="56"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <p>Troli anda kosong</p>
        <small>Tambah design yang anda minati</small>
      </div>
      <div id="csItems"></div>
    </div>
    <div class="cs-ft" id="csFoot">
      <div class="cs-sum">
        <span>Anggaran Harga:</span>
        <strong id="csTotal">RM 0</strong>
      </div>
      <p class="cs-note">*Harga muktamad bergantung pada kuantiti dan design akhir</p>
      <button class="btn-checkout" id="checkoutBtn">
        <svg viewBox="0 0 24 24" fill="currentColor" width="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Order via WhatsApp
      </button>
    </div>
  </aside>

  <!-- PRODUCT MODAL -->
  <div class="modal-ov" id="modalOv"></div>
  <div class="prod-modal" id="prodModal">
    <button class="pm-close" id="pmClose">✕</button>
    <div class="pm-inner" id="pmInner"></div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast"></div>

  <!-- FLOATING WHATSAPP -->
  <a href="https://wa.me/<?= htmlspecialchars($_mns_waNum) ?>" class="float-wa" target="_blank" rel="noopener" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor" width="28"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    <div class="float-wa-ring"></div>
  </a>

  <!-- SCROLL TOP -->
  <button class="scroll-top" id="scrollTop" aria-label="Kembali ke atas">↑</button>

  <script>window.MNS_DATA=<?= json_encode(['products' => $_mns_products, 'waNum' => $_mns_waNum], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
  <script src="js/main.js"></script>
</body>
</html>
