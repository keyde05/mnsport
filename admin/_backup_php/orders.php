<?php
require_once 'includes/auth.php';
$pageTitle   = 'Pesanan';
$currentPage = 'orders';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'status') {
        $allowed = ['baru','dihubungi','proses','siap','batal'];
        $status  = in_array($_POST['status'] ?? '', $allowed) ? $_POST['status'] : 'baru';
        $pdo->prepare('UPDATE mn_orders SET status=? WHERE id=?')->execute([$status, $id]);
        flash_set('ok','Status pesanan dikemaskini.');
    } elseif ($action === 'delete') {
        $pdo->prepare('DELETE FROM mn_orders WHERE id=?')->execute([$id]);
        flash_set('ok','Pesanan dipadam.');
    }
    redirect('orders.php' . (isset($_GET['status']) || isset($_GET['q']) ? '?' . http_build_query(array_filter(['status'=>$_GET['status']??'','q'=>$_GET['q']??''])) : ''));
}

// Filters
$statusFilter = $_GET['status'] ?? '';
$search       = trim($_GET['q'] ?? '');

$where  = 'WHERE 1=1';
$params = [];
if ($statusFilter) { $where .= ' AND status=?'; $params[] = $statusFilter; }
if ($search) {
    $where .= ' AND (name LIKE ? OR phone LIKE ? OR team LIKE ?)';
    $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%";
}

$orders = $pdo->prepare("SELECT * FROM mn_orders $where ORDER BY created_at DESC");
$orders->execute($params);
$orders = $orders->fetchAll();

$statusLabels = [
    'baru'       => ['Baru',         'bg-amber'],
    'dihubungi'  => ['Dihubungi',    'bg-blue'],
    'proses'     => ['Dalam Proses', 'bg-blue'],
    'siap'       => ['Siap',         'bg-green'],
    'batal'      => ['Batal',        'bg-red'],
];

// Detail view
$viewOrder = null;
if (isset($_GET['view'])) {
    $stmt = $pdo->prepare('SELECT * FROM mn_orders WHERE id=?');
    $stmt->execute([$_GET['view']]);
    $viewOrder = $stmt->fetch();
}

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<?php if ($viewOrder): ?>
<!-- ═══ DETAIL VIEW ═══ -->
<div class="card" style="max-width:700px;margin:0 auto">
  <div class="card-hd">
    <h3>📋 Detail Pesanan #<?= $viewOrder['id'] ?></h3>
    <a href="orders.php" class="btn btn-secondary btn-sm">← Kembali</a>
  </div>
  <div class="card-body" style="display:flex;flex-direction:column;gap:1rem">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div>
        <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Pelanggan</div>
        <div style="font-weight:600"><?= h($viewOrder['name']) ?></div>
        <div style="font-size:.82rem;margin-top:.2rem"><?= h($viewOrder['phone']) ?></div>
        <?php if ($viewOrder['team']): ?>
        <div style="font-size:.82rem;color:var(--muted)"><?= h($viewOrder['team']) ?></div>
        <?php endif; ?>
      </div>
      <div>
        <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Tarikh</div>
        <div><?= date('d M Y, H:i', strtotime($viewOrder['created_at'])) ?></div>
        <div style="margin-top:.5rem">
          <span class="badge <?= $statusLabels[$viewOrder['status']][1] ?? 'bg-gray' ?>">
            <?= $statusLabels[$viewOrder['status']][0] ?? h($viewOrder['status']) ?>
          </span>
        </div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:var(--card2);border-radius:8px;padding:.75rem">
      <div>
        <div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Kategori</div>
        <div style="font-size:.84rem;font-weight:600"><?= h($viewOrder['category'] ?: '—') ?></div>
      </div>
      <div>
        <div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Jenis Baju</div>
        <div style="font-size:.84rem;font-weight:600"><?= h($viewOrder['jenis'] ?: '—') ?></div>
      </div>
      <div>
        <div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Design</div>
        <div style="font-size:.84rem"><?= h($viewOrder['design'] ?: '—') ?></div>
      </div>
      <div>
        <div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Jumlah Helai</div>
        <div style="font-size:.84rem;font-weight:700;color:var(--green)"><?= $viewOrder['total_qty'] ?> helai</div>
      </div>
    </div>

    <?php
    $sizes = [];
    if (!empty($viewOrder['sizes_json'])) {
        $sizes = json_decode($viewOrder['sizes_json'], true) ?? [];
    }
    if (array_sum($sizes) > 0):
    ?>
    <div>
      <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem">Saiz</div>
      <div style="display:flex;flex-wrap:wrap;gap:.4rem">
        <?php foreach ($sizes as $sz => $qty): if ($qty < 1) continue; ?>
        <span style="background:var(--card2);border:1px solid var(--border);border-radius:6px;padding:.25rem .65rem;font-size:.78rem">
          <strong><?= strtoupper(h($sz)) ?></strong>: <?= $qty ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($viewOrder['custom_name']): ?>
    <div style="display:flex;align-items:center;gap:.5rem;font-size:.84rem">
      <span style="color:var(--amber)">⚠</span> Ada nama / nombor pada baju
    </div>
    <?php endif; ?>

    <?php if ($viewOrder['notes']): ?>
    <div>
      <div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Catatan</div>
      <div style="background:var(--card2);border-radius:8px;padding:.75rem;font-size:.84rem;white-space:pre-wrap"><?= h($viewOrder['notes']) ?></div>
    </div>
    <?php endif; ?>

    <!-- Status Update -->
    <form method="POST" style="display:flex;gap:.75rem;align-items:flex-end">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="status">
      <input type="hidden" name="id" value="<?= $viewOrder['id'] ?>">
      <div class="f-grp" style="flex:1;margin:0">
        <label>Kemaskini Status</label>
        <select name="status">
          <?php foreach ($statusLabels as $val => [$lbl]): ?>
          <option value="<?= $val ?>" <?= $viewOrder['status']===$val ? 'selected':'' ?>><?= $lbl ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <div style="display:flex;gap:.75rem">
      <?php if ($viewOrder['phone']): ?>
      <?php
        $phone = preg_replace('/[^0-9]/', '', $viewOrder['phone']);
        if (substr($phone, 0, 1) === '0') $phone = '6' . $phone;
        $waMsg  = 'Salam ' . $viewOrder['name'] . '! Berkenaan pesanan custom jersey anda';
        $waMsg .= $viewOrder['design'] ? ' (' . $viewOrder['design'] . ')' : '';
        $waMsg .= '...';
      ?>
      <a href="https://wa.me/<?= $phone ?>?text=<?= urlencode($waMsg) ?>"
         target="_blank" class="btn btn-primary" style="flex:1;justify-content:center">
        💬 WhatsApp Pelanggan
      </a>
      <?php endif; ?>
      <form method="POST" style="display:inline" onsubmit="return confirm('Padam pesanan ini?')">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $viewOrder['id'] ?>">
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="btn btn-danger">🗑 Padam</button>
      </form>
    </div>
  </div>
</div>

<?php else: ?>
<!-- ═══ LIST VIEW ═══ -->

<div class="ph">
  <h2>Pesanan (<?= count($orders) ?>)</h2>
</div>

<form method="GET" class="toolbar">
  <div class="s-input">
    <input type="text" name="q" value="<?= h($search) ?>" placeholder="Cari nama, telefon, pasukan...">
  </div>
  <select name="status" onchange="this.form.submit()">
    <option value="">Semua Status</option>
    <?php foreach ($statusLabels as $val => [$lbl]): ?>
    <option value="<?= $val ?>" <?= $statusFilter===$val ? 'selected':'' ?>><?= $lbl ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-secondary">Cari</button>
  <?php if ($search || $statusFilter): ?><a href="orders.php" class="btn btn-secondary">Reset</a><?php endif; ?>
</form>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Kategori</th>
          <th>Design</th>
          <th>Helai</th>
          <th>Status</th>
          <th>Tarikh</th>
          <th>Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($orders)): ?>
        <tr><td colspan="8" class="empty-state"><p>Tiada pesanan dijumpai.</p></td></tr>
        <?php else: ?>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td style="font-size:.75rem;color:var(--muted)">#<?= $o['id'] ?></td>
          <td>
            <div style="font-weight:600;font-size:.84rem"><?= h($o['name']) ?></div>
            <div style="font-size:.72rem;color:var(--muted)"><?= h($o['phone']) ?></div>
            <?php if ($o['team']): ?><div style="font-size:.72rem;color:var(--muted)"><?= h($o['team']) ?></div><?php endif; ?>
          </td>
          <td><span class="badge bg-gray" style="font-size:.62rem"><?= h($o['category'] ?: '—') ?></span></td>
          <td style="font-size:.78rem;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= h($o['design'] ?: '—') ?></td>
          <td style="font-weight:700;color:var(--green)"><?= $o['total_qty'] ?></td>
          <td>
            <form method="POST" style="display:inline">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="status">
              <input type="hidden" name="id" value="<?= $o['id'] ?>">
              <select name="status" onchange="this.closest('form').submit()" style="font-size:.75rem;padding:.2em .4em;background:var(--card2);border:1px solid var(--border);border-radius:5px;color:var(--text)">
                <?php foreach ($statusLabels as $val => [$lbl]): ?>
                <option value="<?= $val ?>" <?= $o['status']===$val ? 'selected':'' ?>><?= $lbl ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td style="font-size:.78rem;color:var(--muted)"><?= date('d/m/y H:i', strtotime($o['created_at'])) ?></td>
          <td style="white-space:nowrap">
            <a href="orders.php?view=<?= $o['id'] ?>" class="btn btn-secondary btn-xs">👁 Lihat</a>
            <?php if ($o['phone']): ?>
            <?php $ph = preg_replace('/[^0-9]/','',$o['phone']); if(substr($ph,0,1)==='0') $ph='6'.$ph; ?>
            <a href="https://wa.me/<?= $ph ?>" target="_blank" class="btn btn-secondary btn-xs">💬</a>
            <?php endif; ?>
            <form method="POST" style="display:inline" onsubmit="return confirm('Padam pesanan ini?')">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $o['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <button type="submit" class="btn btn-danger btn-xs">🗑</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php endif; ?>

<?php include 'includes/layout-foot.php'; ?>
