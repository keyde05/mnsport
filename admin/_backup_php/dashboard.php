<?php
require_once 'includes/auth.php';
$pageTitle   = 'Dashboard';
$currentPage = 'dashboard';

// Stats produk & pesanan
$totalProducts = $pdo->query('SELECT COUNT(*) FROM mn_products WHERE active=1')->fetchColumn();
$totalAll      = $pdo->query('SELECT COUNT(*) FROM mn_products')->fetchColumn();
$totalFeatured = $pdo->query('SELECT COUNT(*) FROM mn_products WHERE featured=1')->fetchColumn();
$totalCats     = $pdo->query('SELECT COUNT(*) FROM mn_categories WHERE active=1')->fetchColumn();
$totalOrders   = $pdo->query('SELECT COUNT(*) FROM mn_orders')->fetchColumn();
$newOrders     = $pdo->query('SELECT COUNT(*) FROM mn_orders WHERE status="baru"')->fetchColumn();

// === ANALISIS KEWANGAN ===
$avgPrice    = (float)$pdo->query('SELECT COALESCE(AVG(price),0) FROM mn_products WHERE active=1 AND price>0')->fetchColumn();
$totalHelai  = (int)$pdo->query('SELECT COALESCE(SUM(total_qty),0) FROM mn_orders')->fetchColumn();
$helaiSiap   = (int)$pdo->query('SELECT COALESCE(SUM(total_qty),0) FROM mn_orders WHERE status="siap"')->fetchColumn();
$helaiAktif  = (int)$pdo->query('SELECT COALESCE(SUM(total_qty),0) FROM mn_orders WHERE status NOT IN ("batal")')->fetchColumn();
$helaiMonth  = (int)$pdo->query('SELECT COALESCE(SUM(total_qty),0) FROM mn_orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())')->fetchColumn();
$ordersMonth = (int)$pdo->query('SELECT COUNT(*) FROM mn_orders WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())')->fetchColumn();

$nilaiTotal = $totalHelai * $avgPrice;
$nilaiSiap  = $helaiSiap  * $avgPrice;
$nilaiMonth = $helaiMonth  * $avgPrice;

// Trend bulanan 6 bulan
$monthlyData = $pdo->query(
    "SELECT DATE_FORMAT(created_at,'%Y-%m') AS bulan,
            COUNT(*) AS cnt,
            COALESCE(SUM(total_qty),0) AS qty
     FROM mn_orders
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MONTH)
     GROUP BY bulan ORDER BY bulan ASC"
)->fetchAll();
$maxQty = max(array_column($monthlyData, 'qty') ?: [1]);
if ($maxQty == 0) $maxQty = 1;

// Breakdown status
$statusQty = $pdo->query(
    "SELECT status, COUNT(*) AS cnt, COALESCE(SUM(total_qty),0) AS qty
     FROM mn_orders GROUP BY status"
)->fetchAll();

// Pesanan mengikut kategori
$catOrders = $pdo->query(
    "SELECT category, COUNT(*) AS cnt, COALESCE(SUM(total_qty),0) AS qty
     FROM mn_orders
     WHERE category IS NOT NULL AND category != ''
     GROUP BY category ORDER BY qty DESC LIMIT 5"
)->fetchAll();
$maxCatQty = max(array_column($catOrders, 'qty') ?: [1]);
if ($maxCatQty == 0) $maxCatQty = 1;

// Produk mengikut kategori
$catStats = $pdo->query(
    'SELECT c.name, c.icon, COUNT(p.id) AS cnt
     FROM mn_categories c
     LEFT JOIN mn_products p ON p.category_id=c.id AND p.active=1
     WHERE c.active=1
     GROUP BY c.id ORDER BY c.sort_order'
)->fetchAll();

// Produk terkini
$recentProducts = $pdo->query(
    'SELECT p.*, c.name AS cat_name, c.icon AS cat_icon
     FROM mn_products p
     LEFT JOIN mn_categories c ON c.id = p.category_id
     ORDER BY p.created_at DESC LIMIT 6'
)->fetchAll();

$waNum = setting($pdo, 'wa_num', '—');

$statusLabels = [
    'baru'      => ['Baru',         'bg-amber'],
    'dihubungi' => ['Dihubungi',    'bg-blue'],
    'proses'    => ['Dalam Proses', 'bg-blue'],
    'siap'      => ['Siap',         'bg-green'],
    'batal'     => ['Batal',        'bg-red'],
];

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<!-- STAT CARDS — Produk & Pesanan -->
<div class="stat-grid">
  <div class="stat-card green">
    <div class="sc-label">Produk Aktif</div>
    <div class="sc-val"><?= $totalProducts ?></div>
    <div class="sc-sub"><?= $totalAll ?> jumlah keseluruhan</div>
  </div>
  <div class="stat-card amber">
    <div class="sc-label">Produk Featured</div>
    <div class="sc-val"><?= $totalFeatured ?></div>
    <div class="sc-sub">Dipapar di halaman utama</div>
  </div>
  <div class="stat-card blue">
    <div class="sc-label">Kategori Aktif</div>
    <div class="sc-val"><?= $totalCats ?></div>
    <div class="sc-sub">Football · Casual · Formal</div>
  </div>
  <div class="stat-card red">
    <div class="sc-label">Pesanan Baru</div>
    <div class="sc-val"><?= $newOrders ?></div>
    <div class="sc-sub"><?= $totalOrders ?> jumlah keseluruhan</div>
  </div>
</div>

<!-- STAT CARDS — Kewangan -->
<div class="stat-grid" style="margin-bottom:1.5rem">
  <div class="stat-card" style="border-color:rgba(34,197,94,.25)">
    <div class="sc-label">💰 Est. Nilai Keseluruhan</div>
    <div class="sc-val" style="font-size:1.45rem;color:var(--green)">RM <?= number_format($nilaiTotal,0) ?></div>
    <div class="sc-sub"><?= number_format($totalHelai) ?> helai × avg RM <?= number_format($avgPrice,2) ?></div>
  </div>
  <div class="stat-card" style="border-color:rgba(34,197,94,.15)">
    <div class="sc-label">✅ Est. Nilai Siap</div>
    <div class="sc-val" style="font-size:1.45rem;color:var(--green)">RM <?= number_format($nilaiSiap,0) ?></div>
    <div class="sc-sub"><?= number_format($helaiSiap) ?> helai telah siap</div>
  </div>
  <div class="stat-card amber">
    <div class="sc-label">📅 Helai Bulan Ini</div>
    <div class="sc-val"><?= number_format($helaiMonth) ?></div>
    <div class="sc-sub"><?= $ordersMonth ?> pesanan · est. RM <?= number_format($nilaiMonth,0) ?></div>
  </div>
  <div class="stat-card blue">
    <div class="sc-label">📦 Helai Aktif (Tanpa Batal)</div>
    <div class="sc-val" style="color:#60a5fa"><?= number_format($helaiAktif) ?></div>
    <div class="sc-sub">Dalam proses / belum siap</div>
  </div>
</div>

<!-- TREND BULANAN + STATUS BREAKDOWN -->
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

  <!-- Bar Chart 6 Bulan -->
  <div class="card">
    <div class="card-hd"><h3>📈 Trend Helai Pesanan (6 Bulan)</h3></div>
    <div class="card-body">
      <?php if (empty($monthlyData)): ?>
      <div style="color:var(--muted);font-size:.82rem;text-align:center;padding:2.5rem">Tiada data lagi</div>
      <?php else: ?>
      <div style="display:flex;align-items:flex-end;gap:.6rem;height:150px;margin-bottom:.6rem">
        <?php foreach ($monthlyData as $m):
          $barH = max(6, round(($m['qty'] / $maxQty) * 140));
        ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:.25rem">
          <div style="font-size:.6rem;color:var(--muted);font-weight:700"><?= $m['qty'] ?></div>
          <div style="width:100%;height:<?= $barH ?>px;background:linear-gradient(to top,var(--accent),rgba(230,48,18,.5));border-radius:4px 4px 0 0" title="<?= date('M Y', strtotime($m['bulan'].'-01')) ?>: <?= $m['qty'] ?> helai"></div>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:.6rem;border-top:1px solid var(--border);padding-top:.5rem">
        <?php foreach ($monthlyData as $m): ?>
        <div style="flex:1;text-align:center;font-size:.6rem;color:var(--muted)"><?= date('M', strtotime($m['bulan'].'-01')) ?></div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:.65rem;font-size:.7rem;color:var(--muted);text-align:right">* Berdasarkan jumlah helai dalam setiap pesanan</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Status Breakdown -->
  <div class="card">
    <div class="card-hd"><h3>📊 Ringkasan Status</h3></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:.55rem">
      <?php foreach ($statusLabels as $val => [$lbl, $cls]):
        $row = array_values(array_filter($statusQty, fn($r) => $r['status'] === $val));
        $cnt = $row[0]['cnt'] ?? 0;
        $qty = $row[0]['qty'] ?? 0;
      ?>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:.45rem .65rem;background:var(--card2);border-radius:7px">
        <div style="display:flex;align-items:center;gap:.5rem">
          <span class="badge <?= $cls ?>"><?= $lbl ?></span>
          <span style="font-size:.7rem;color:var(--muted)"><?= $cnt ?> pesanan</span>
        </div>
        <span style="font-size:.8rem;font-weight:700"><?= number_format($qty) ?> helai</span>
      </div>
      <?php endforeach; ?>
      <div style="margin-top:.3rem;padding:.5rem .65rem;background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:7px;display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:.72rem;font-weight:700;color:var(--green)">JUMLAH</span>
        <span style="font-size:.82rem;font-weight:800;color:var(--green)"><?= number_format($totalHelai) ?> helai</span>
      </div>
    </div>
  </div>
</div>

<!-- PESANAN KATEGORI + INFO TETAPAN -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">

  <!-- Pesanan Mengikut Kategori -->
  <div class="card">
    <div class="card-hd">
      <h3><?= !empty($catOrders) ? '👕 Pesanan Mengikut Kategori' : '📊 Produk Mengikut Kategori' ?></h3>
    </div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:.8rem">
      <?php if (!empty($catOrders)): ?>
        <?php foreach ($catOrders as $co): ?>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.3rem">
            <span style="font-weight:600"><?= h(ucfirst($co['category'])) ?></span>
            <span style="color:var(--muted)"><?= number_format($co['qty']) ?> helai · <?= $co['cnt'] ?> pesanan</span>
          </div>
          <div style="background:var(--border);border-radius:4px;height:5px;overflow:hidden">
            <div style="background:var(--accent);height:100%;width:<?= round($co['qty']/$maxCatQty*100) ?>%;border-radius:4px"></div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php foreach ($catStats as $cat): ?>
        <div style="display:flex;align-items:center;gap:.75rem">
          <span style="font-size:1.1rem;width:24px"><?= h($cat['icon']) ?></span>
          <div style="flex:1">
            <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.3rem">
              <span><?= h($cat['name']) ?></span>
              <span style="font-weight:700"><?= $cat['cnt'] ?></span>
            </div>
            <div style="background:var(--border);border-radius:4px;height:5px;overflow:hidden">
              <div style="background:var(--accent);height:100%;width:<?= $totalProducts > 0 ? round($cat['cnt']/$totalProducts*100) : 0 ?>%;border-radius:4px"></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Info Tetapan -->
  <div class="card">
    <div class="card-hd"><h3>⚙️ Info Tetapan</h3></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:.65rem">
      <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--muted)">Nombor WhatsApp</span>
        <span style="font-weight:600;color:var(--green)">+<?= h($waNum) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--muted)">Email Syarikat</span>
        <span><?= h(setting($pdo,'site_email')) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)">
        <span style="color:var(--muted)">Minimum Order</span>
        <span><?= h(setting($pdo,'min_order','10')) ?> helai</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0">
        <span style="color:var(--muted)">Avg. Harga Produk</span>
        <span style="font-weight:600;color:var(--amber)">RM <?= number_format($avgPrice,2) ?></span>
      </div>
      <a href="settings.php" class="btn btn-secondary btn-sm" style="margin-top:.5rem;justify-content:center">
        Tukar Tetapan →
      </a>
    </div>
  </div>
</div>

<!-- PRODUK TERKINI -->
<div class="card">
  <div class="card-hd">
    <h3>🕒 Produk Terkini</h3>
    <a href="products.php" class="btn btn-secondary btn-sm">Lihat Semua</a>
  </div>
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Produk</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Featured</th>
          <th>Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentProducts as $p): ?>
        <tr>
          <td><img src="../<?= h($p['image']) ?>" alt="" class="p-thumb" onerror="this.style.display='none'"></td>
          <td>
            <div style="font-weight:600"><?= h($p['name']) ?></div>
            <div style="font-size:.7rem;color:var(--muted)"><?= h($p['design']) ?></div>
          </td>
          <td><?= h($p['cat_icon']??'') ?> <?= h($p['cat_name']??'—') ?></td>
          <td>RM <?= number_format($p['price'],2) ?></td>
          <td><span class="badge <?= $p['active'] ? 'bg-green' : 'bg-gray' ?>"><?= $p['active'] ? 'Aktif' : 'Tidak Aktif' ?></span></td>
          <td><span class="badge <?= $p['featured'] ? 'bg-amber' : 'bg-gray' ?>"><?= $p['featured'] ? '★ Ya' : 'Tidak' ?></span></td>
          <td>
            <a href="product-form.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/layout-foot.php'; ?>
