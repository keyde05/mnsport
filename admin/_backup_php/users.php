<?php
require_once 'includes/auth.php';

if (!is_superadmin()) {
    flash_set('err','Akses ditolak. Superadmin sahaja boleh urus pengguna.');
    redirect('dashboard.php');
}

$pageTitle   = 'Pengguna';
$currentPage = 'users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    switch ($action) {
        case 'add':
        case 'edit':
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email']    ?? '');
            $role     = $_POST['role'] === 'superadmin' ? 'superadmin' : 'admin';
            $active   = isset($_POST['active']) ? 1 : 0;
            $password = $_POST['password'] ?? '';

            if (!$username || !$email) { flash_set('err','Username dan email wajib diisi.'); break; }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { flash_set('err','Format email tidak sah.'); break; }

            if ($action === 'edit' && $id) {
                // Cannot demote self
                if ($id === (int)$adminUser['id'] && $role !== 'superadmin') {
                    flash_set('err','Anda tidak boleh turunkan peranan akaun sendiri.'); break;
                }
                $params = [$username, $email, $role, $active, $id];
                $sql = 'UPDATE mn_users SET username=?, email=?, role=?, active=? WHERE id=?';
                if ($password) {
                    if (strlen($password) < 8) { flash_set('err','Password min. 8 aksara.'); break; }
                    $sql = 'UPDATE mn_users SET username=?, email=?, role=?, active=?, password_hash=? WHERE id=?';
                    $params = [$username, $email, $role, $active, password_hash($password, PASSWORD_BCRYPT), $id];
                }
                $pdo->prepare($sql)->execute($params);
                flash_set('ok','Pengguna dikemaskini.');
            } else {
                if (!$password || strlen($password) < 8) { flash_set('err','Password min. 8 aksara wajib diisi untuk akaun baru.'); break; }
                try {
                    $pdo->prepare('INSERT INTO mn_users (username,email,password_hash,role,active) VALUES (?,?,?,?,?)')
                        ->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT), $role, $active]);
                    flash_set('ok','Pengguna baru ditambah.');
                } catch (PDOException $e) {
                    flash_set('err','Username atau email sudah wujud.');
                }
            }
            break;

        case 'toggle':
            if ($id === (int)$adminUser['id']) { flash_set('err','Tidak boleh nyahaktif akaun sendiri.'); break; }
            $pdo->prepare('UPDATE mn_users SET active = NOT active WHERE id=?')->execute([$id]);
            flash_set('ok','Status dikemaskini.');
            break;

        case 'delete':
            if ($id === (int)$adminUser['id']) { flash_set('err','Tidak boleh padam akaun sendiri.'); break; }
            $pdo->prepare('DELETE FROM mn_users WHERE id=?')->execute([$id]);
            flash_set('ok','Pengguna dipadam.');
            break;
    }
    redirect('users.php');
}

$users = $pdo->query('SELECT * FROM mn_users ORDER BY role, username')->fetchAll();

// Edit mode
$editing = null;
if (isset($_GET['edit'])) {
    foreach ($users as $u) {
        if ($u['id'] == $_GET['edit']) { $editing = $u; break; }
    }
}

include 'includes/layout-head.php';
?>

<?= flash_html() ?>

<div style="display:grid;grid-template-columns:1fr 360px;gap:1.25rem;align-items:start">

  <!-- LEFT — Table -->
  <div class="card">
    <div class="card-hd"><h3>Semua Pengguna (<?= count($users) ?>)</h3></div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Peranan</th>
            <th>Aktif</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:.6rem">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0">
                  <?= strtoupper(substr($u['username'],0,1)) ?>
                </div>
                <div>
                  <div style="font-weight:600;font-size:.84rem"><?= h($u['username']) ?></div>
                  <?php if ($u['id'] === (int)$adminUser['id']): ?>
                  <div style="font-size:.7rem;color:var(--muted)">(Anda)</div>
                  <?php endif; ?>
                </div>
              </div>
            </td>
            <td style="font-size:.82rem"><?= h($u['email']) ?></td>
            <td>
              <span class="badge <?= $u['role']==='superadmin' ? 'bg-amber' : 'bg-green' ?>">
                <?= $u['role']==='superadmin' ? '👑 Superadmin' : 'Admin' ?>
              </span>
            </td>
            <td>
              <?php if ($u['id'] !== (int)$adminUser['id']): ?>
              <form method="POST" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="toggle">
                <label class="toggle"><input type="checkbox" <?= $u['active'] ? 'checked':'' ?> onchange="this.closest('form').submit()"><span class="tgl-sl"></span></label>
              </form>
              <?php else: ?>
              <span class="badge bg-green">Aktif</span>
              <?php endif; ?>
            </td>
            <td style="white-space:nowrap">
              <a href="users.php?edit=<?= $u['id'] ?>" class="btn btn-secondary btn-xs">✏ Edit</a>
              <?php if ($u['id'] !== (int)$adminUser['id']): ?>
              <form method="POST" style="display:inline" onsubmit="return confirm('Padam pengguna <?= h($u['username']) ?>?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger btn-xs">🗑</button>
              </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- RIGHT — Form -->
  <div class="card">
    <div class="card-hd">
      <h3><?= $editing ? '✏ Edit Pengguna' : '＋ Tambah Pengguna' ?></h3>
      <?php if ($editing): ?><a href="users.php" class="btn btn-secondary btn-sm">Batal</a><?php endif; ?>
    </div>
    <div class="card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:.75rem">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="<?= $editing ? 'edit' : 'add' ?>">
        <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>

        <div class="f-grp">
          <label>Username *</label>
          <input type="text" name="username" value="<?= h($editing['username'] ?? '') ?>" placeholder="cth: admin1" required>
        </div>
        <div class="f-grp">
          <label>Email *</label>
          <input type="email" name="email" value="<?= h($editing['email'] ?? '') ?>" placeholder="admin@mnsports.my" required>
        </div>
        <div class="f-grp">
          <label>Password <?= $editing ? '<span class="f-hint">(biarkan kosong jika tidak tukar)</span>' : '<span class="f-hint">(min. 8 aksara)</span>' ?></label>
          <input type="password" name="password" placeholder="<?= $editing ? 'Kosongkan jika tidak tukar' : 'Password baru' ?>">
        </div>
        <div class="f-grp">
          <label>Peranan</label>
          <select name="role">
            <option value="admin" <?= ($editing['role']??'admin')==='admin' ? 'selected':'' ?>>Admin</option>
            <option value="superadmin" <?= ($editing['role']??'')==='superadmin' ? 'selected':'' ?>>👑 Superadmin</option>
          </select>
        </div>
        <label style="display:flex;align-items:center;gap:.65rem;cursor:pointer;font-size:.85rem">
          <input type="checkbox" name="active" <?= ($editing['active'] ?? 1) ? 'checked':'' ?> style="width:16px;height:16px">
          <span>Aktif</span>
        </label>
        <button type="submit" class="btn btn-primary" style="justify-content:center;margin-top:.25rem">
          <?= $editing ? '💾 Simpan' : '＋ Tambah Pengguna' ?>
        </button>
      </form>
    </div>
  </div>

</div>

<?php include 'includes/layout-foot.php'; ?>
