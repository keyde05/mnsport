<?php
require_once 'includes/auth.php';

$id      = (int)($_GET['id'] ?? 0);
$product = null;
$isEdit  = false;

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM mn_products WHERE id=?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) { flash_set('err','Produk tidak dijumpai.'); redirect('products.php'); }
    $isEdit = true;
}

$pageTitle   = $isEdit ? 'Edit Produk' : 'Tambah Produk';
$currentPage = 'products';
$breadcrumb  = '<a href="products.php">Produk</a> <span>›</span> ' . ($isEdit ? 'Edit' : 'Tambah');

$categories = $pdo->query('SELECT * FROM mn_categories WHERE active=1 ORDER BY sort_order')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $data = [
        'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
        'code'        => trim($_POST['code']    ?? ''),
        'name'        => trim($_POST['name']    ?? ''),
        'design'      => trim($_POST['design']  ?? ''),
        'price'       => (float)($_POST['price'] ?? 0),
        'colors'      => trim($_POST['colors']  ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'badge'       => trim($_POST['badge']   ?? ''),
        'badge_class' => trim($_POST['badge_class'] ?? ''),
        'featured'    => isset($_POST['featured']) ? 1 : 0,
        'active'      => isset($_POST['active'])   ? 1 : 0,
        'sort_order'  => (int)($_POST['sort_order'] ?? 0),
    ];

    if (!$data['name']) { $error = 'Nama produk wajib diisi.'; }

    // Handle image upload
    $imagePath = $product['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $file     = $_FILES['image'];
        $allowed  = ['image/jpeg','image/png','image/webp'];
        $maxSize  = 3 * 1024 * 1024;

        if (!in_array($file['type'], $allowed)) {
            $error = 'Format gambar tidak disokong. Guna JPG, PNG, atau WebP.';
        } elseif ($file['size'] > $maxSize) {
            $error = 'Saiz gambar melebihi 3MB.';
        } else {
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename  = strtolower(preg_replace('/[^a-z0-9]/i','_', $data['code'] ?: $data['name'])) . '.' . $ext;
            $destDir   = __DIR__ . '/../baju/';
            $dest      = $destDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $imagePath = 'baju/' . $filename;
            } else {
                $error = 'Gagal muat naik gambar. Semak permission folder baju/.';
            }
        }
    }
    $data['image'] = $imagePath;

    if (!$error) {
        if ($isEdit) {
            $sql = 'UPDATE mn_products SET category_id=:category_id, code=:code, name=:name, design=:design,
                    image=:image, price=:price, colors=:colors, description=:description,
                    badge=:badge, badge_class=:badge_class, featured=:featured, active=:active,
                    sort_order=:sort_order WHERE id=:id';
            $data['id'] = $id;
        } else {
            $sql = 'INSERT INTO mn_products (category_id,code,name,design,image,price,colors,description,badge,badge_class,featured,active,sort_order)
                    VALUES (:category_id,:code,:name,:design,:image,:price,:colors,:description,:badge,:badge_class,:featured,:active,:sort_order)';
        }
        $pdo->prepare($sql)->execute($data);
        flash_set('ok', $isEdit ? 'Produk berjaya dikemaskini.' : 'Produk berjaya ditambah.');
        redirect('products.php');
    }
}

$v = $product ?? $_POST;
include 'includes/layout-head.php';
?>

<?php if ($error): ?>
<div class="flash flash-err"><?= h($error) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:start">

    <!-- LEFT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:1.25rem">
      <div class="card">
        <div class="card-hd"><h3>Maklumat Produk</h3></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="f-grp full">
              <label>Nama Produk *</label>
              <input type="text" name="name" value="<?= h($v['name'] ?? '') ?>" placeholder="cth: Classic Polo Pro" required>
            </div>
            <div class="f-grp">
              <label>Kod Produk</label>
              <input type="text" name="code" value="<?= h($v['code'] ?? '') ?>" placeholder="cth: p000">
            </div>
            <div class="f-grp">
              <label>Nama Design</label>
              <input type="text" name="design" value="<?= h($v['design'] ?? '') ?>" placeholder="cth: Design #000">
            </div>
            <div class="f-grp">
              <label>Harga (RM) / helai *</label>
              <input type="number" name="price" value="<?= h($v['price'] ?? '0') ?>" min="0" step="0.01">
            </div>
            <div class="f-grp">
              <label>Warna Tersedia</label>
              <input type="text" name="colors" value="<?= h($v['colors'] ?? '') ?>" placeholder="cth: Hitam / Merah / Biru">
            </div>
            <div class="f-grp full">
              <label>Deskripsi</label>
              <textarea name="description" rows="3" placeholder="Terangkan produk ini..."><?= h($v['description'] ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-hd"><h3>Badge & Susunan</h3></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="f-grp">
              <label>Teks Badge</label>
              <input type="text" name="badge" value="<?= h($v['badge'] ?? '') ?>" placeholder="cth: Popular / New / Hot">
            </div>
            <div class="f-grp">
              <label>Kelas Badge</label>
              <select name="badge_class">
                <option value="" <?= ($v['badge_class']??'')=='' ? 'selected':'' ?>>Default (tiada)</option>
                <option value="dark" <?= ($v['badge_class']??'')=='dark' ? 'selected':'' ?>>Dark</option>
                <option value="gold" <?= ($v['badge_class']??'')=='gold' ? 'selected':'' ?>>Gold</option>
              </select>
            </div>
            <div class="f-grp">
              <label>Susunan (sort_order)</label>
              <input type="number" name="sort_order" value="<?= h($v['sort_order'] ?? '0') ?>" min="0">
              <span class="f-hint">Nombor kecil = dipapar dahulu</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:1.25rem">
      <div class="card">
        <div class="card-hd"><h3>Kategori</h3></div>
        <div class="card-body">
          <div class="f-grp">
            <label>Pilih Kategori</label>
            <select name="category_id">
              <option value="">— Tiada —</option>
              <?php foreach ($categories as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($v['category_id']??'')==$c['id'] ? 'selected':'' ?>>
                <?= h($c['icon']) ?> <?= h($c['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-hd"><h3>Gambar Produk</h3></div>
        <div class="card-body">
          <?php if (!empty($product['image'])): ?>
          <img src="../<?= h($product['image']) ?>" class="img-prev" id="imgPreview" style="width:100%;height:160px;object-fit:cover;margin-bottom:.75rem">
          <?php else: ?>
          <div id="imgPreview" style="width:100%;height:120px;background:var(--card2);border-radius:7px;border:1px dashed var(--border);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:.8rem;margin-bottom:.75rem">Tiada gambar</div>
          <?php endif; ?>
          <div class="f-grp">
            <label>Muat Naik Gambar</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                   onchange="previewImg(this)">
            <span class="f-hint">JPG / PNG / WebP · Maks 3MB</span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-hd"><h3>Penerbitan</h3></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:.75rem">
          <label style="display:flex;align-items:center;gap:.65rem;cursor:pointer;font-size:.85rem">
            <input type="checkbox" name="active" <?= ($v['active']??1) ? 'checked':'' ?> style="width:16px;height:16px">
            <span>Aktif (dipapar di laman)</span>
          </label>
          <label style="display:flex;align-items:center;gap:.65rem;cursor:pointer;font-size:.85rem">
            <input type="checkbox" name="featured" <?= ($v['featured']??0) ? 'checked':'' ?> style="width:16px;height:16px">
            <span>Featured (dipapar di halaman utama)</span>
          </label>
        </div>
      </div>

      <div style="display:flex;gap:.75rem">
        <a href="products.php" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</a>
        <button type="submit" class="btn btn-primary" style="flex:2;justify-content:center">
          <?= $isEdit ? '💾 Simpan Perubahan' : '＋ Tambah Produk' ?>
        </button>
      </div>
    </div>

  </div>
</form>

<script>
function previewImg(input) {
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    const el = document.getElementById('imgPreview');
    el.innerHTML = '';
    Object.assign(el.style, {background:'none',border:'none'});
    const img = document.createElement('img');
    img.src = e.target.result;
    Object.assign(img.style, {width:'100%',height:'160px',objectFit:'cover',borderRadius:'7px'});
    el.appendChild(img);
  };
  reader.readAsDataURL(input.files[0]);
}
</script>

<?php include 'includes/layout-foot.php'; ?>
