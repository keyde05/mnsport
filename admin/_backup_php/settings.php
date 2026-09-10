<?php
require_once 'includes/auth.php';
$pageTitle   = 'Tetapan';
$currentPage = 'settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $keys = [
        'wa_num', 'wa_display', 'site_name', 'site_email',
        'site_ig', 'site_fb', 'site_tt', 'min_order',
        'hero_tagline', 'hero_sub',
    ];

    $stmt = $pdo->prepare('INSERT INTO mn_settings (`key`, value)
                            VALUES (:k, :v)
                            ON DUPLICATE KEY UPDATE value=:v');

    foreach ($keys as $key) {
        $val = trim($_POST[$key] ?? '');
        $stmt->execute([':k' => $key, ':v' => $val]);
    }

    // Regenerate config.php so public pages pick up new values
    regen_config($pdo);

    flash_set('ok','Tetapan berjaya disimpan. config.php telah dikemaskini.');
    redirect('settings.php');
}

// Load all current settings
$rows = $pdo->query('SELECT `key`, value FROM mn_settings')->fetchAll(PDO::FETCH_KEY_PAIR);
$s = function($key, $default='') use ($rows) { return $rows[$key] ?? $default; };

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<form method="POST">
  <?= csrf_field() ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

    <!-- WhatsApp -->
    <div class="card">
      <div class="card-hd">
        <h3>💬 WhatsApp</h3>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div class="f-grp">
          <label>Nombor WA (tanpa +)</label>
          <input type="text" name="wa_num" value="<?= h($s('wa_num','60123456789')) ?>" placeholder="601XXXXXXXX">
          <span class="f-hint">Contoh: 60123456789 (tanpa +, tanpa sengkang)</span>
        </div>
        <div class="f-grp">
          <label>Nombor Paparan</label>
          <input type="text" name="wa_display" value="<?= h($s('wa_display','+60 12-345 6789')) ?>" placeholder="+60 12-345 6789">
          <span class="f-hint">Dipapar di footer & halaman hubungi</span>
        </div>
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:.75rem;font-size:.78rem;line-height:1.6">
          <strong>Link WA akan jadi:</strong><br>
          <code style="color:var(--green)">https://wa.me/<?= h($s('wa_num','60123456789')) ?></code>
        </div>
      </div>
    </div>

    <!-- Site Info -->
    <div class="card">
      <div class="card-hd"><h3>🏢 Maklumat Syarikat</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div class="f-grp">
          <label>Nama Syarikat</label>
          <input type="text" name="site_name" value="<?= h($s('site_name','MN Sports')) ?>" placeholder="MN Sports">
        </div>
        <div class="f-grp">
          <label>Email</label>
          <input type="email" name="site_email" value="<?= h($s('site_email','info@mnsports.my')) ?>" placeholder="info@mnsports.my">
        </div>
        <div class="f-grp">
          <label>Minimum Order (helai)</label>
          <input type="number" name="min_order" value="<?= h($s('min_order','10')) ?>" min="1" placeholder="10">
        </div>
      </div>
    </div>

    <!-- Social Media -->
    <div class="card">
      <div class="card-hd"><h3>📱 Media Sosial</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div class="f-grp">
          <label>Instagram URL</label>
          <input type="url" name="site_ig" value="<?= h($s('site_ig')) ?>" placeholder="https://instagram.com/mnsports">
        </div>
        <div class="f-grp">
          <label>Facebook URL <span class="f-hint">(kosong = sembunyi)</span></label>
          <input type="url" name="site_fb" value="<?= h($s('site_fb')) ?>" placeholder="https://facebook.com/mnsports">
        </div>
        <div class="f-grp">
          <label>TikTok URL <span class="f-hint">(kosong = sembunyi)</span></label>
          <input type="url" name="site_tt" value="<?= h($s('site_tt')) ?>" placeholder="https://tiktok.com/@mnsports">
        </div>
      </div>
    </div>

    <!-- Hero / Banner -->
    <div class="card">
      <div class="card-hd"><h3>🖼 Hero / Banner Utama</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
        <div class="f-grp">
          <label>Tagline Utama</label>
          <input type="text" name="hero_tagline" value="<?= h($s('hero_tagline','JERSI SUBLIMASI TERBAIK')) ?>" placeholder="JERSI SUBLIMASI TERBAIK">
          <span class="f-hint">Teks besar di hero banner</span>
        </div>
        <div class="f-grp">
          <label>Sub Tagline</label>
          <input type="text" name="hero_sub" value="<?= h($s('hero_sub','Kualiti premium, harga berpatutan')) ?>" placeholder="Kualiti premium, harga berpatutan">
        </div>
      </div>
    </div>

  </div>

  <div style="margin-top:1.25rem;display:flex;justify-content:flex-end">
    <button type="submit" class="btn btn-primary" style="padding:.65rem 2rem">
      💾 Simpan Semua Tetapan
    </button>
  </div>
</form>

<?php include 'includes/layout-foot.php'; ?>
