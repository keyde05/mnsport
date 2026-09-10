<?php
require_once 'includes/auth.php';
$pageTitle   = 'Kategori';
$currentPage = 'categories';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    switch ($action) {
        case 'add':
        case 'edit':
            $data = [
                'name'       => trim($_POST['name']  ?? ''),
                'slug'       => trim($_POST['slug']  ?? ''),
                'icon'       => trim($_POST['icon']  ?? ''),
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'active'     => isset($_POST['active']) ? 1 : 0,
            ];
            if (!$data['name']) { flash_set('err','Nama kategori wajib diisi.'); break; }
            if (!$data['slug']) { $data['slug'] = strtolower(preg_replace('/[^a-z0-9]/i','-',$data['name'])); }

            if ($action === 'edit' && $id) {
                $data['id'] = $id;
                $pdo->prepare('UPDATE mn_categories SET name=:name, slug=:slug, icon=:icon, sort_order=:sort_order, active=:active WHERE id=:id')
                    ->execute($data);
                flash_set('ok','Kategori berjaya dikemaskini.');
            } else {
                $pdo->prepare('INSERT INTO mn_categories (name,slug,icon,sort_order,active) VALUES (:name,:slug,:icon,:sort_order,:active)')
                    ->execute($data);
                flash_set('ok','Kategori berjaya ditambah.');
            }
            break;

        case 'toggle':
            $pdo->prepare('UPDATE mn_categories SET active = NOT active WHERE id=?')->execute([$id]);
            flash_set('ok','Status dikemaskini.');
            break;

        case 'delete':
            $inUse = $pdo->prepare('SELECT COUNT(*) FROM mn_products WHERE category_id=?');
            $inUse->execute([$id]);
            if ($inUse->fetchColumn() > 0) {
                flash_set('err','Tidak boleh padam — kategori ini masih ada produk.');
            } else {
                $pdo->prepare('DELETE FROM mn_categories WHERE id=?')->execute([$id]);
                flash_set('ok','Kategori dipadam.');
            }
            break;
    }
    redirect('categories.php');
}

$categories = $pdo->query(
    'SELECT c.*, COUNT(p.id) AS product_count
     FROM mn_categories c
     LEFT JOIN mn_products p ON p.category_id=c.id
     GROUP BY c.id ORDER BY c.sort_order'
)->fetchAll();

// Edit mode — load existing
$editing = null;
if (isset($_GET['edit'])) {
    foreach ($categories as $c) {
        if ($c['id'] == $_GET['edit']) { $editing = $c; break; }
    }
}

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:1.25rem;align-items:start">

  <!-- LEFT — Table -->
  <div class="card">
    <div class="card-hd"><h3>Semua Kategori (<?= count($categories) ?>)</h3></div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>Ikon</th>
            <th>Nama</th>
            <th>Slug</th>
            <th>Produk</th>
            <th>Susunan</th>
            <th>Aktif</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($categories)): ?>
          <tr><td colspan="7" class="empty-state"><p>Tiada kategori lagi.</p></td></tr>
          <?php else: ?>
          <?php foreach ($categories as $c): ?>
          <tr>
            <td style="font-size:1.4rem;text-align:center"><?= h($c['icon']) ?></td>
            <td style="font-weight:600"><?= h($c['name']) ?></td>
            <td><code style="font-size:.75rem;background:var(--card2);padding:.1em .4em;border-radius:4px"><?= h($c['slug']) ?></code></td>
            <td><?= $c['product_count'] ?></td>
            <td><?= h($c['sort_order']) ?></td>
            <td>
              <form method="POST" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="action" value="toggle">
                <label class="toggle"><input type="checkbox" <?= $c['active'] ? 'checked':'' ?> onchange="this.closest('form').submit()"><span class="tgl-sl"></span></label>
              </form>
            </td>
            <td style="white-space:nowrap">
              <a href="categories.php?edit=<?= $c['id'] ?>" class="btn btn-secondary btn-xs">✏ Edit</a>
              <?php if ($c['product_count'] == 0): ?>
              <form method="POST" style="display:inline" onsubmit="return confirm('Padam kategori ini?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger btn-xs">🗑</button>
              </form>
              <?php else: ?>
              <button class="btn btn-secondary btn-xs" disabled title="Ada produk dalam kategori ini">🗑</button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- RIGHT — Form -->
  <div class="card">
    <div class="card-hd">
      <h3><?= $editing ? '✏ Edit Kategori' : '＋ Tambah Kategori' ?></h3>
      <?php if ($editing): ?><a href="categories.php" class="btn btn-secondary btn-sm">Batal</a><?php endif; ?>
    </div>
    <div class="card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:.75rem">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="<?= $editing ? 'edit' : 'add' ?>">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>

        <div class="f-grp">
          <label>Nama Kategori *</label>
          <input type="text" name="name" value="<?= h($editing['name'] ?? '') ?>" placeholder="cth: Football" required>
        </div>
        <div class="f-grp">
          <label>Slug</label>
          <input type="text" name="slug" value="<?= h($editing['slug'] ?? '') ?>" placeholder="cth: football (auto-jana jika kosong)">
          <span class="f-hint">Digunakan dalam URL</span>
        </div>
        <div class="f-grp">
          <label>Ikon (Emoji)</label>
          <input type="text" name="icon" value="<?= h($editing['icon'] ?? '') ?>" placeholder="⚽">
        </div>
        <div class="f-grp">
          <label>Susunan</label>
          <input type="number" name="sort_order" value="<?= h($editing['sort_order'] ?? '0') ?>" min="0">
        </div>
        <label style="display:flex;align-items:center;gap:.65rem;cursor:pointer;font-size:.85rem">
          <input type="checkbox" name="active" <?= ($editing['active'] ?? 1) ? 'checked':'' ?> style="width:16px;height:16px">
          <span>Aktif</span>
        </label>
        <button type="submit" class="btn btn-primary" style="justify-content:center;margin-top:.25rem">
          <?= $editing ? '💾 Simpan' : '＋ Tambah' ?>
        </button>
      </form>
    </div>
  </div>

</div>

<?php include 'includes/layout-foot.php'; ?>
