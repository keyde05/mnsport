<?php
require_once 'includes/auth.php';
$pageTitle   = 'Produk';
$currentPage = 'products';

// Handle quick actions (toggle active/featured/delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id     = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'toggle_active':
            $pdo->prepare('UPDATE mn_products SET active = NOT active WHERE id=?')->execute([$id]);
            flash_set('ok', 'Status produk dikemaskini.');
            break;
        case 'toggle_featured':
            $pdo->prepare('UPDATE mn_products SET featured = NOT featured WHERE id=?')->execute([$id]);
            flash_set('ok', 'Status featured dikemaskini.');
            break;
        case 'delete':
            $pdo->prepare('DELETE FROM mn_products WHERE id=?')->execute([$id]);
            flash_set('ok', 'Produk berjaya dipadam.');
            break;
    }
    redirect('products.php' . (isset($_GET['cat']) ? '?cat='.$_GET['cat'] : ''));
}

// Filters
$catFilter = $_GET['cat'] ?? '';
$search    = trim($_GET['q'] ?? '');

$where  = 'WHERE 1=1';
$params = [];
if ($catFilter) { $where .= ' AND p.category_id=?'; $params[] = $catFilter; }
if ($search)    { $where .= ' AND (p.name LIKE ? OR p.design LIKE ? OR p.code LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }

$products = $pdo->prepare(
    "SELECT p.*, c.name AS cat_name, c.icon AS cat_icon
     FROM mn_products p
     LEFT JOIN mn_categories c ON c.id=p.category_id
     $where ORDER BY p.sort_order, p.created_at DESC"
);
$products->execute($params);
$products = $products->fetchAll();

$categories = $pdo->query('SELECT * FROM mn_categories WHERE active=1 ORDER BY sort_order')->fetchAll();

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<div class="ph">
  <h2>Semua Produk (<?= count($products) ?>)</h2>
  <a href="product-form.php" class="btn btn-primary">＋ Tambah Produk</a>
</div>

<!-- TOOLBAR -->
<form method="GET" class="toolbar">
  <div class="s-input">
    <input type="text" name="q" value="<?= h($search) ?>" placeholder="Cari nama, design, kod...">
  </div>
  <select name="cat" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    <?php foreach ($categories as $c): ?>
    <option value="<?= $c['id'] ?>" <?= $catFilter == $c['id'] ? 'selected' : '' ?>>
      <?= h($c['icon']) ?> <?= h($c['name']) ?>
    </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-secondary">Cari</button>
  <?php if ($search || $catFilter): ?>
  <a href="products.php" class="btn btn-secondary">Reset</a>
  <?php endif; ?>
</form>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:52px">Gambar</th>
          <th>Produk</th>
          <th>Kategori</th>
          <th>Harga</th>
          <th>Aktif</th>
          <th>Featured</th>
          <th>Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
        <tr><td colspan="7" class="empty-state"><p>Tiada produk dijumpai.</p></td></tr>
        <?php else: ?>
        <?php foreach ($products as $p): ?>
        <tr>
          <td>
            <img src="../<?= h($p['image']) ?>" alt="" class="p-thumb"
                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2244%22 height=%2244%22><rect width=%2244%22 height=%2244%22 fill=%22%23333%22/></svg>'">
          </td>
          <td>
            <div style="font-weight:600;font-size:.85rem"><?= h($p['name']) ?></div>
            <div style="font-size:.7rem;color:var(--muted)"><?= h($p['design']) ?> · <?= h($p['code']) ?></div>
          </td>
          <td><?= h($p['cat_icon']??'') ?> <?= h($p['cat_name']??'—') ?></td>
          <td><strong>RM <?= number_format($p['price'],2) ?></strong><span style="font-size:.7rem;color:var(--muted)">/helai</span></td>
          <td>
            <form method="POST" style="display:inline">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="hidden" name="action" value="toggle_active">
              <label class="toggle" title="Toggle Aktif">
                <input type="checkbox" <?= $p['active'] ? 'checked' : '' ?> onchange="this.closest('form').submit()">
                <span class="tgl-sl"></span>
              </label>
            </form>
          </td>
          <td>
            <form method="POST" style="display:inline">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <input type="hidden" name="action" value="toggle_featured">
              <label class="toggle" title="Toggle Featured">
                <input type="checkbox" <?= $p['featured'] ? 'checked' : '' ?> onchange="this.closest('form').submit()">
                <span class="tgl-sl"></span>
              </label>
            </form>
          </td>
          <td style="white-space:nowrap">
            <a href="product-form.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-xs">✏ Edit</a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Padam produk ini?')">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

<?php include 'includes/layout-foot.php'; ?>
