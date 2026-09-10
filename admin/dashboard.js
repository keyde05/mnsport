/* ══════════════════════════════════════
   MN SPORTS — Admin Dashboard (Static / localStorage)
   Tiada server/database — semua data disimpan dalam browser.
══════════════════════════════════════ */

const DB_KEY      = 'mns_admin_v1';
const SESSION_KEY = 'mns_admin_session';

const STATUS_LABELS = {
  baru:      ['Baru',         'bg-amber'],
  dihubungi: ['Dihubungi',    'bg-blue'],
  proses:    ['Dalam Proses', 'bg-blue'],
  siap:      ['Siap',         'bg-green'],
  batal:     ['Batal',        'bg-red'],
};

/* ── UTIL ───────────────────────────────── */
function esc(s) {
  return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
function money(n) { return Number(n || 0).toLocaleString('en-MY', {minimumFractionDigits: 0, maximumFractionDigits: 2}); }
function nextId(list) { return list.length ? Math.max(...list.map(x => x.id)) + 1 : 1; }
function fmtDate(iso, withTime) {
  const d = new Date(iso);
  if (isNaN(d)) return '—';
  const opts = {day:'2-digit', month:'short', year:'numeric'};
  let out = d.toLocaleDateString('en-GB', opts);
  if (withTime) out += ', ' + d.toTimeString().slice(0,5);
  return out;
}
function showFlash(type, msg) {
  const host = document.getElementById('flashHost');
  const el = document.createElement('div');
  el.className = 'flash ' + (type === 'ok' ? 'flash-ok' : 'flash-err');
  el.textContent = (type === 'ok' ? '✓ ' : '✗ ') + msg;
  host.appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

/* ── DATA STORE ─────────────────────────── */
function seedData() {
  return {
    categories: [
      {id:1, name:'Football', slug:'football', icon:'⚽', sort_order:1, active:1},
      {id:2, name:'Casual',   slug:'casual',   icon:'👕', sort_order:2, active:1},
      {id:3, name:'Formal',   slug:'formal',   icon:'👔', sort_order:3, active:1},
    ],
    products: [
      {id:1, category_id:2, code:'p000',  name:'Classic Polo Pro', design:'Design #000',    image:'baju/000.jpg',   price:55, colors:'Hitam / Maroon / Oren',  description:'Polo sublimasi premium dengan corak diagonal dinamik.', badge:'Popular',    badge_class:'',     featured:1, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:2, category_id:1, code:'p093',  name:'Brush Art Jersey', design:'Design #093',    image:'baju/093.jpg',   price:48, colors:'Hitam / Pink',           description:'Jersey dengan corak brush stroke yang energetik.',      badge:'New',        badge_class:'dark', featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:3, category_id:3, code:'p103',  name:'Corporate Marine', design:'Design #103',    image:'baju/103.jpg',   price:55, colors:'Merah Gelap / Emas',     description:'Jersey korporat premium dengan ilustrasi industri.',    badge:'',           badge_class:'',     featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:4, category_id:3, code:'p106',  name:'Royal Baroque',    design:'Design #106',    image:'baju/106.jpg',   price:52, colors:'Hitam / Emas',           description:'Jersey eksklusif dengan corak baroque mewah.',          badge:'Exclusive',  badge_class:'gold', featured:1, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:5, category_id:1, code:'p106n9',name:'Football Strike',  design:'Design #106 N9', image:'baju/106n9.jpg', price:45, colors:'Kuning / Hitam / Merah', description:'Jersey bola sepak gaya bersih dengan warna terang.',    badge:'Bestseller', badge_class:'',     featured:1, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:6, category_id:1, code:'p108',  name:'Ocean Splash',     design:'Design #108',    image:'baju/108.jpg',   price:48, colors:'Biru / Hijau / Kuning',  description:'Jersey sukan dengan corak percikan warna cerah.',       badge:'',           badge_class:'',     featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:7, category_id:2, code:'p109',  name:'Geometric Fade',   design:'Design #109',    image:'baju/109.jpg',   price:55, colors:'Pink / Ungu',            description:'Polo dengan corak geometrik gradient yang cantik.',     badge:'Popular',    badge_class:'',     featured:1, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:8, category_id:1, code:'phtjs', name:'Teal Marble',      design:'Design HTJS',    image:'baju/htjs.jpg',  price:48, colors:'Teal / Hitam',           description:'Jersey dengan corak marble mewah berwarna teal.',       badge:'',           badge_class:'',     featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:9, category_id:3, code:'pik',   name:'Heritage Polo',    design:'Design IK',      image:'baju/ik.jpg',    price:55, colors:'Putih / Merah / Oren',   description:'Polo heritage dengan corak geometrik bold.',            badge:'',           badge_class:'',     featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
      {id:10,category_id:2, code:'psgmc', name:'Speed Racer',      design:'Design SGMC3',   image:'baju/sgmc.jpg',  price:52, colors:'Hitam / Multi-warna',    description:'Jersey bertenaga tinggi dengan corak brush multi-warna.',badge:'Hot',        badge_class:'',     featured:0, active:1, sort_order:0, created_at:new Date().toISOString()},
    ],
    orders: [],
    users: [
      {id:1, username:'admin', email:'admin@mnsports.my', password:'admin123', role:'superadmin', active:1, last_login:null},
    ],
    settings: {
      wa_num:'60123456789', wa_display:'+60 12-345 6789', site_name:'MN Sports', site_email:'info@mnsports.my',
      min_order:'10', site_ig:'https://instagram.com/mnsports', site_fb:'', site_tt:'',
      hero_tagline:'JERSI SUBLIMASI TERBAIK', hero_sub:'Kualiti premium, harga berpatutan',
    },
  };
}

let DATA = null;
function loadData() {
  try {
    const raw = localStorage.getItem(DB_KEY);
    DATA = raw ? JSON.parse(raw) : seedData();
  } catch (e) { DATA = seedData(); }
  if (!DATA.orders) DATA.orders = [];
  saveData();
}
function saveData() {
  try { localStorage.setItem(DB_KEY, JSON.stringify(DATA)); }
  catch (e) { showFlash('err', 'Gagal simpan data — storan browser mungkin penuh.'); }
}

/* ── AUTH ───────────────────────────────── */
function getSession() {
  try { return JSON.parse(localStorage.getItem(SESSION_KEY)); } catch (e) { return null; }
}
function setSession(userId) { localStorage.setItem(SESSION_KEY, JSON.stringify({userId})); }
function clearSession() { localStorage.removeItem(SESSION_KEY); }
function currentUser() {
  const s = getSession();
  if (!s) return null;
  return DATA.users.find(u => u.id === s.userId && u.active) || null;
}
function isSuperadmin() { const u = currentUser(); return !!u && u.role === 'superadmin'; }

function doLogin(username, password) {
  const u = DATA.users.find(x => (x.username === username || x.email === username) && x.active);
  if (!u || u.password !== password) return false;
  u.last_login = new Date().toISOString();
  saveData();
  setSession(u.id);
  return true;
}

/* ── ROUTER ─────────────────────────────── */
const PAGE_TITLES = {
  dashboard:'Dashboard', products:'Produk', categories:'Kategori',
  orders:'Pesanan', settings:'Tetapan', users:'Pengguna',
};
let currentPage = 'dashboard';

function showPage(page) {
  if (page === 'users' && !isSuperadmin()) {
    showFlash('err', 'Akses ditolak. Superadmin sahaja boleh urus pengguna.');
    page = 'dashboard';
  }
  currentPage = page;
  document.querySelectorAll('#app > .main .content > section').forEach(s => s.hidden = (s.id !== 'page-' + page));
  document.querySelectorAll('.sb-link[data-page]').forEach(b => b.classList.toggle('active', b.dataset.page === page));
  document.getElementById('pageTitle').textContent = PAGE_TITLES[page] || '';
  location.hash = page;
  renderPage(page);
}
function renderPage(page) {
  if (page === 'dashboard')  renderDashboard();
  if (page === 'products')   renderProducts();
  if (page === 'categories') renderCategories();
  if (page === 'orders')     renderOrders();
  if (page === 'settings')   renderSettings();
  if (page === 'users')      renderUsers();
}

/* ── MODALS ─────────────────────────────── */
function openModal(id) { document.getElementById(id).hidden = false; }
function closeModal(id) { document.getElementById(id).hidden = true; }
document.addEventListener('click', e => {
  if (e.target.dataset.closeModal) closeModal(e.target.dataset.closeModal);
  if (e.target.classList.contains('modal-overlay')) e.target.hidden = true;
});

/* ══════════════════════════════════════
   DASHBOARD
══════════════════════════════════════ */
function renderDashboard() {
  const { products, categories, orders } = DATA;
  const totalProducts = products.filter(p => p.active).length;
  const totalAll      = products.length;
  const totalFeatured = products.filter(p => p.featured).length;
  const totalCats     = categories.filter(c => c.active).length;
  const totalOrders   = orders.length;
  const newOrders     = orders.filter(o => o.status === 'baru').length;

  const activePriced = products.filter(p => p.active && p.price > 0);
  const avgPrice = activePriced.length ? activePriced.reduce((s,p) => s+Number(p.price),0) / activePriced.length : 0;

  const sumQty = list => list.reduce((s,o) => s + Number(o.total_qty || 0), 0);
  const totalHelai = sumQty(orders);
  const helaiSiap  = sumQty(orders.filter(o => o.status === 'siap'));
  const helaiAktif = sumQty(orders.filter(o => o.status !== 'batal'));
  const now = new Date();
  const thisMonthOrders = orders.filter(o => { const d = new Date(o.created_at); return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear(); });
  const helaiMonth  = sumQty(thisMonthOrders);
  const ordersMonth = thisMonthOrders.length;

  const nilaiTotal = totalHelai * avgPrice;
  const nilaiSiap  = helaiSiap * avgPrice;
  const nilaiMonth = helaiMonth * avgPrice;

  document.getElementById('statGridMain').innerHTML = `
    <div class="stat-card green"><div class="sc-label">Produk Aktif</div><div class="sc-val">${totalProducts}</div><div class="sc-sub">${totalAll} jumlah keseluruhan</div></div>
    <div class="stat-card amber"><div class="sc-label">Produk Featured</div><div class="sc-val">${totalFeatured}</div><div class="sc-sub">Dipapar di halaman utama</div></div>
    <div class="stat-card blue"><div class="sc-label">Kategori Aktif</div><div class="sc-val">${totalCats}</div><div class="sc-sub">Football · Casual · Formal</div></div>
    <div class="stat-card red"><div class="sc-label">Pesanan Baru</div><div class="sc-val">${newOrders}</div><div class="sc-sub">${totalOrders} jumlah keseluruhan</div></div>`;

  document.getElementById('statGridFinance').innerHTML = `
    <div class="stat-card" style="border-color:rgba(34,197,94,.25)"><div class="sc-label">💰 Est. Nilai Keseluruhan</div><div class="sc-val" style="font-size:1.45rem;color:var(--green)">RM ${money(nilaiTotal)}</div><div class="sc-sub">${money(totalHelai)} helai × avg RM ${avgPrice.toFixed(2)}</div></div>
    <div class="stat-card" style="border-color:rgba(34,197,94,.15)"><div class="sc-label">✅ Est. Nilai Siap</div><div class="sc-val" style="font-size:1.45rem;color:var(--green)">RM ${money(nilaiSiap)}</div><div class="sc-sub">${money(helaiSiap)} helai telah siap</div></div>
    <div class="stat-card amber"><div class="sc-label">📅 Helai Bulan Ini</div><div class="sc-val">${money(helaiMonth)}</div><div class="sc-sub">${ordersMonth} pesanan · est. RM ${money(nilaiMonth)}</div></div>
    <div class="stat-card blue"><div class="sc-label">📦 Helai Aktif (Tanpa Batal)</div><div class="sc-val" style="color:#60a5fa">${money(helaiAktif)}</div><div class="sc-sub">Dalam proses / belum siap</div></div>`;

  // Trend 6 months
  const months = [];
  for (let i = 5; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    months.push({key: d.getFullYear()+'-'+d.getMonth(), label: d.toLocaleDateString('en-GB',{month:'short'}), qty:0});
  }
  orders.forEach(o => {
    const d = new Date(o.created_at);
    const key = d.getFullYear()+'-'+d.getMonth();
    const m = months.find(x => x.key === key);
    if (m) m.qty += Number(o.total_qty || 0);
  });
  const maxQty = Math.max(1, ...months.map(m => m.qty));
  document.getElementById('trendChart').innerHTML = `
    <div class="bar-chart">${months.map(m => `<div class="bar-col"><div class="bar-val">${m.qty}</div><div class="bar-fill" style="height:${Math.max(6, Math.round(m.qty/maxQty*140))}px" title="${m.qty} helai"></div></div>`).join('')}</div>
    <div class="bar-labels">${months.map(m => `<div>${m.label}</div>`).join('')}</div>
    <div style="margin-top:.65rem;font-size:.7rem;color:var(--muted);text-align:right">* Berdasarkan jumlah helai dalam setiap pesanan</div>`;

  // Status breakdown
  let statusHtml = '';
  Object.entries(STATUS_LABELS).forEach(([val, [lbl, cls]]) => {
    const list = orders.filter(o => o.status === val);
    statusHtml += `<div style="display:flex;align-items:center;justify-content:space-between;padding:.45rem .65rem;background:var(--card2);border-radius:7px">
      <div style="display:flex;align-items:center;gap:.5rem"><span class="badge ${cls}">${lbl}</span><span style="font-size:.7rem;color:var(--muted)">${list.length} pesanan</span></div>
      <span style="font-size:.8rem;font-weight:700">${money(sumQty(list))} helai</span></div>`;
  });
  statusHtml += `<div style="margin-top:.3rem;padding:.5rem .65rem;background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:7px;display:flex;justify-content:space-between;align-items:center">
    <span style="font-size:.72rem;font-weight:700;color:var(--green)">JUMLAH</span><span style="font-size:.82rem;font-weight:800;color:var(--green)">${money(totalHelai)} helai</span></div>`;
  document.getElementById('statusBreakdown').innerHTML = statusHtml;

  // Category orders / product stats
  const catMap = {};
  orders.forEach(o => { if (o.category) { catMap[o.category] = catMap[o.category] || {cnt:0,qty:0}; catMap[o.category].cnt++; catMap[o.category].qty += Number(o.total_qty||0); } });
  const catOrders = Object.entries(catMap).map(([category, v]) => ({category, ...v})).sort((a,b) => b.qty - a.qty).slice(0,5);

  if (catOrders.length) {
    const maxCatQty = Math.max(1, ...catOrders.map(c => c.qty));
    document.getElementById('catOrdersTitle').innerHTML = '<h3>👕 Pesanan Mengikut Kategori</h3>';
    document.getElementById('catOrdersBody').innerHTML = catOrders.map(co => `
      <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.3rem">
        <span style="font-weight:600">${esc(co.category.charAt(0).toUpperCase()+co.category.slice(1))}</span>
        <span style="color:var(--muted)">${money(co.qty)} helai · ${co.cnt} pesanan</span></div>
        <div class="progress-track"><div class="progress-fill" style="width:${Math.round(co.qty/maxCatQty*100)}%"></div></div></div>`).join('');
  } else {
    document.getElementById('catOrdersTitle').innerHTML = '<h3>📊 Produk Mengikut Kategori</h3>';
    const catStats = categories.filter(c => c.active).sort((a,b)=>a.sort_order-b.sort_order).map(c => ({
      ...c, cnt: products.filter(p => p.active && p.category_id === c.id).length,
    }));
    document.getElementById('catOrdersBody').innerHTML = catStats.map(c => `
      <div style="display:flex;align-items:center;gap:.75rem">
        <span style="font-size:1.1rem;width:24px">${esc(c.icon)}</span>
        <div style="flex:1"><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.3rem"><span>${esc(c.name)}</span><span style="font-weight:700">${c.cnt}</span></div>
        <div class="progress-track"><div class="progress-fill" style="width:${totalProducts>0?Math.round(c.cnt/totalProducts*100):0}%"></div></div></div></div>`).join('');
  }

  // Settings info
  const s = DATA.settings;
  document.getElementById('settingsInfo').innerHTML = `
    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)"><span style="color:var(--muted)">Nombor WhatsApp</span><span style="font-weight:600;color:var(--green)">+${esc(s.wa_num)}</span></div>
    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)"><span style="color:var(--muted)">Email Syarikat</span><span>${esc(s.site_email)}</span></div>
    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0;border-bottom:1px solid var(--border)"><span style="color:var(--muted)">Minimum Order</span><span>${esc(s.min_order)} helai</span></div>
    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:.5rem 0"><span style="color:var(--muted)">Avg. Harga Produk</span><span style="font-weight:600;color:var(--amber)">RM ${avgPrice.toFixed(2)}</span></div>
    <button class="btn btn-secondary btn-sm" style="margin-top:.5rem;justify-content:center" data-page="settings">Tukar Tetapan →</button>`;

  // Recent products
  const recent = [...products].sort((a,b) => new Date(b.created_at) - new Date(a.created_at)).slice(0,6);
  document.getElementById('recentProductsBody').innerHTML = recent.map(p => {
    const cat = categories.find(c => c.id === p.category_id);
    return `<tr>
      <td><img src="${prodImgSrc(p)}" class="p-thumb" alt=""></td>
      <td><div style="font-weight:600">${esc(p.name)}</div><div style="font-size:.7rem;color:var(--muted)">${esc(p.design)}</div></td>
      <td>${cat ? esc(cat.icon)+' '+esc(cat.name) : '—'}</td>
      <td>RM ${Number(p.price).toFixed(2)}</td>
      <td><span class="badge ${p.active?'bg-green':'bg-gray'}">${p.active?'Aktif':'Tidak Aktif'}</span></td>
      <td><span class="badge ${p.featured?'bg-amber':'bg-gray'}">${p.featured?'★ Ya':'Tidak'}</span></td>
    </tr>`;
  }).join('') || `<tr><td colspan="6" class="empty-state"><p>Tiada produk lagi.</p></td></tr>`;
}

function prodImgSrc(p) {
  if (!p.image) return placeholderImg();
  if (p.image.startsWith('data:')) return p.image;
  return '../' + p.image;
}
function placeholderImg() {
  return 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44"><rect width="44" height="44" fill="#333"/></svg>');
}

/* ══════════════════════════════════════
   PRODUCTS
══════════════════════════════════════ */
let prodFilterCat = '', prodFilterQ = '';

function renderProducts() {
  const catSel = document.getElementById('prodCatFilter');
  catSel.innerHTML = '<option value="">Semua Kategori</option>' + DATA.categories.filter(c=>c.active).map(c => `<option value="${c.id}" ${String(c.id)===prodFilterCat?'selected':''}>${esc(c.icon)} ${esc(c.name)}</option>`).join('');

  let list = [...DATA.products];
  if (prodFilterCat) list = list.filter(p => String(p.category_id) === String(prodFilterCat));
  if (prodFilterQ) {
    const q = prodFilterQ.toLowerCase();
    list = list.filter(p => (p.name||'').toLowerCase().includes(q) || (p.design||'').toLowerCase().includes(q) || (p.code||'').toLowerCase().includes(q));
  }
  list.sort((a,b) => (a.sort_order-b.sort_order) || (new Date(b.created_at)-new Date(a.created_at)));

  document.getElementById('productsCount').textContent = `Semua Produk (${list.length})`;
  document.getElementById('productsBody').innerHTML = list.map(p => {
    const cat = DATA.categories.find(c => c.id === p.category_id);
    return `<tr>
      <td><img src="${prodImgSrc(p)}" class="p-thumb" alt=""></td>
      <td><div style="font-weight:600;font-size:.85rem">${esc(p.name)}</div><div style="font-size:.7rem;color:var(--muted)">${esc(p.design)} · ${esc(p.code)}</div></td>
      <td>${cat ? esc(cat.icon)+' '+esc(cat.name) : '—'}</td>
      <td><strong>RM ${Number(p.price).toFixed(2)}</strong><span style="font-size:.7rem;color:var(--muted)">/helai</span></td>
      <td><label class="toggle"><input type="checkbox" ${p.active?'checked':''} onchange="toggleProduct(${p.id},'active')"><span class="tgl-sl"></span></label></td>
      <td><label class="toggle"><input type="checkbox" ${p.featured?'checked':''} onchange="toggleProduct(${p.id},'featured')"><span class="tgl-sl"></span></label></td>
      <td style="white-space:nowrap">
        <button class="btn btn-secondary btn-xs" onclick="editProduct(${p.id})">✏ Edit</button>
        <button class="btn btn-danger btn-xs" onclick="deleteProduct(${p.id})">🗑</button>
      </td>
    </tr>`;
  }).join('') || `<tr><td colspan="7" class="empty-state"><p>Tiada produk dijumpai.</p></td></tr>`;
}

function toggleProduct(id, field) {
  const p = DATA.products.find(x => x.id === id);
  if (!p) return;
  p[field] = p[field] ? 0 : 1;
  saveData();
  showFlash('ok', 'Status produk dikemaskini.');
  renderProducts();
}
function deleteProduct(id) {
  if (!confirm('Padam produk ini?')) return;
  DATA.products = DATA.products.filter(p => p.id !== id);
  saveData();
  showFlash('ok', 'Produk berjaya dipadam.');
  renderProducts();
}

let editingProductImage = null;
function openProductModal(product) {
  editingProductImage = product ? product.image : '';
  document.getElementById('productModalTitle').textContent = product ? 'Edit Produk' : 'Tambah Produk';
  document.getElementById('productSubmitBtn').textContent = product ? '💾 Simpan Perubahan' : '＋ Tambah Produk';
  document.getElementById('p_id').value = product ? product.id : '';
  document.getElementById('p_name').value = product?.name || '';
  document.getElementById('p_code').value = product?.code || '';
  document.getElementById('p_design').value = product?.design || '';
  document.getElementById('p_price').value = product?.price ?? 0;
  document.getElementById('p_colors').value = product?.colors || '';
  document.getElementById('p_description').value = product?.description || '';
  document.getElementById('p_badge').value = product?.badge || '';
  document.getElementById('p_badge_class').value = product?.badge_class || '';
  document.getElementById('p_sort_order').value = product?.sort_order ?? 0;
  document.getElementById('p_active').checked = product ? !!product.active : true;
  document.getElementById('p_featured').checked = product ? !!product.featured : false;
  document.getElementById('p_image').value = '';

  const catSel = document.getElementById('p_category_id');
  catSel.innerHTML = '<option value="">— Tiada —</option>' + DATA.categories.filter(c=>c.active).map(c => `<option value="${c.id}" ${c.id===product?.category_id?'selected':''}>${esc(c.icon)} ${esc(c.name)}</option>`).join('');

  const preview = document.getElementById('p_imgPreview');
  if (product && product.image) {
    preview.innerHTML = `<img src="${prodImgSrc(product)}" style="width:100%;height:120px;object-fit:cover">`;
  } else {
    preview.textContent = 'Tiada gambar';
  }
  openModal('productModal');
}
document.getElementById('btnAddProduct').addEventListener('click', () => openProductModal(null));
function editProduct(id) { openProductModal(DATA.products.find(p => p.id === id)); }

document.getElementById('p_image').addEventListener('change', function () {
  if (!this.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    editingProductImage = e.target.result;
    document.getElementById('p_imgPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:120px;object-fit:cover">`;
  };
  reader.readAsDataURL(this.files[0]);
});

document.getElementById('productForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const id = document.getElementById('p_id').value;
  const name = document.getElementById('p_name').value.trim();
  if (!name) { showFlash('err', 'Nama produk wajib diisi.'); return; }
  const data = {
    category_id: document.getElementById('p_category_id').value ? Number(document.getElementById('p_category_id').value) : null,
    code: document.getElementById('p_code').value.trim(),
    name, design: document.getElementById('p_design').value.trim(),
    price: Number(document.getElementById('p_price').value) || 0,
    colors: document.getElementById('p_colors').value.trim(),
    description: document.getElementById('p_description').value.trim(),
    badge: document.getElementById('p_badge').value.trim(),
    badge_class: document.getElementById('p_badge_class').value,
    featured: document.getElementById('p_featured').checked ? 1 : 0,
    active: document.getElementById('p_active').checked ? 1 : 0,
    sort_order: Number(document.getElementById('p_sort_order').value) || 0,
    image: editingProductImage || '',
  };
  if (id) {
    const p = DATA.products.find(x => x.id === Number(id));
    Object.assign(p, data);
    showFlash('ok', 'Produk berjaya dikemaskini.');
  } else {
    data.id = nextId(DATA.products);
    data.created_at = new Date().toISOString();
    DATA.products.push(data);
    showFlash('ok', 'Produk berjaya ditambah.');
  }
  saveData();
  closeModal('productModal');
  renderProducts();
});

document.getElementById('prodSearch').addEventListener('input', function () { prodFilterQ = this.value.trim(); renderProducts(); });
document.getElementById('prodCatFilter').addEventListener('change', function () { prodFilterCat = this.value; renderProducts(); });
document.getElementById('btnProdReset').addEventListener('click', () => { prodFilterQ = ''; prodFilterCat = ''; document.getElementById('prodSearch').value=''; renderProducts(); });

/* ══════════════════════════════════════
   CATEGORIES
══════════════════════════════════════ */
let editingCatId = null;

function renderCategories() {
  const cats = [...DATA.categories].sort((a,b) => a.sort_order - b.sort_order);
  document.getElementById('categoriesCount').textContent = `Semua Kategori (${cats.length})`;
  document.getElementById('categoriesBody').innerHTML = cats.map(c => {
    const count = DATA.products.filter(p => p.category_id === c.id).length;
    return `<tr>
      <td style="font-size:1.4rem;text-align:center">${esc(c.icon)}</td>
      <td style="font-weight:600">${esc(c.name)}</td>
      <td><code>${esc(c.slug)}</code></td>
      <td>${count}</td>
      <td>${esc(c.sort_order)}</td>
      <td><label class="toggle"><input type="checkbox" ${c.active?'checked':''} onchange="toggleCategory(${c.id})"><span class="tgl-sl"></span></label></td>
      <td style="white-space:nowrap">
        <button class="btn btn-secondary btn-xs" onclick="editCategory(${c.id})">✏ Edit</button>
        ${count===0 ? `<button class="btn btn-danger btn-xs" onclick="deleteCategory(${c.id})">🗑</button>` : `<button class="btn btn-secondary btn-xs" disabled title="Ada produk dalam kategori ini">🗑</button>`}
      </td>
    </tr>`;
  }).join('') || `<tr><td colspan="7" class="empty-state"><p>Tiada kategori lagi.</p></td></tr>`;
}
function toggleCategory(id) {
  const c = DATA.categories.find(x => x.id === id);
  c.active = c.active ? 0 : 1;
  saveData(); showFlash('ok','Status dikemaskini.'); renderCategories();
}
function deleteCategory(id) {
  const inUse = DATA.products.some(p => p.category_id === id);
  if (inUse) { showFlash('err','Tidak boleh padam — kategori ini masih ada produk.'); return; }
  if (!confirm('Padam kategori ini?')) return;
  DATA.categories = DATA.categories.filter(c => c.id !== id);
  saveData(); showFlash('ok','Kategori dipadam.'); renderCategories();
}
function editCategory(id) {
  const c = DATA.categories.find(x => x.id === id);
  editingCatId = id;
  document.getElementById('catFormTitle').textContent = '✏ Edit Kategori';
  document.getElementById('catSubmitBtn').textContent = '💾 Simpan';
  document.getElementById('btnCatCancel').hidden = false;
  document.getElementById('catId').value = c.id;
  document.getElementById('catName').value = c.name;
  document.getElementById('catSlug').value = c.slug;
  document.getElementById('catIcon').value = c.icon;
  document.getElementById('catSort').value = c.sort_order;
  document.getElementById('catActive').checked = !!c.active;
}
function resetCatForm() {
  editingCatId = null;
  document.getElementById('catFormTitle').textContent = '＋ Tambah Kategori';
  document.getElementById('catSubmitBtn').textContent = '＋ Tambah';
  document.getElementById('btnCatCancel').hidden = true;
  document.getElementById('catForm').reset();
  document.getElementById('catId').value = '';
  document.getElementById('catActive').checked = true;
}
document.getElementById('btnCatCancel').addEventListener('click', resetCatForm);
document.getElementById('catForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const name = document.getElementById('catName').value.trim();
  if (!name) { showFlash('err','Nama kategori wajib diisi.'); return; }
  let slug = document.getElementById('catSlug').value.trim();
  if (!slug) slug = name.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
  const data = { name, slug, icon: document.getElementById('catIcon').value.trim(), sort_order: Number(document.getElementById('catSort').value)||0, active: document.getElementById('catActive').checked?1:0 };
  if (editingCatId) {
    Object.assign(DATA.categories.find(c => c.id === editingCatId), data);
    showFlash('ok','Kategori berjaya dikemaskini.');
  } else {
    data.id = nextId(DATA.categories);
    DATA.categories.push(data);
    showFlash('ok','Kategori berjaya ditambah.');
  }
  saveData();
  resetCatForm();
  renderCategories();
});

/* ══════════════════════════════════════
   ORDERS
══════════════════════════════════════ */
let orderFilterStatus = '', orderFilterQ = '';

function renderOrders() {
  let list = [...DATA.orders];
  if (orderFilterStatus) list = list.filter(o => o.status === orderFilterStatus);
  if (orderFilterQ) {
    const q = orderFilterQ.toLowerCase();
    list = list.filter(o => (o.name||'').toLowerCase().includes(q) || (o.phone||'').toLowerCase().includes(q) || (o.team||'').toLowerCase().includes(q));
  }
  list.sort((a,b) => new Date(b.created_at) - new Date(a.created_at));

  document.getElementById('ordersCount').textContent = `Pesanan (${list.length})`;
  document.getElementById('ordersBody').innerHTML = list.map(o => {
    const [lbl] = STATUS_LABELS[o.status] || ['—',''];
    let ph = (o.phone||'').replace(/[^0-9]/g,''); if (ph.startsWith('0')) ph = '6'+ph;
    return `<tr>
      <td style="font-size:.75rem;color:var(--muted)">#${o.id}</td>
      <td><div style="font-weight:600;font-size:.84rem">${esc(o.name)}</div><div style="font-size:.72rem;color:var(--muted)">${esc(o.phone)}</div>${o.team?`<div style="font-size:.72rem;color:var(--muted)">${esc(o.team)}</div>`:''}</td>
      <td><span class="badge bg-gray" style="font-size:.62rem">${esc(o.category||'—')}</span></td>
      <td style="font-size:.78rem;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${esc(o.design||'—')}</td>
      <td style="font-weight:700;color:var(--green)">${o.total_qty||0}</td>
      <td><select onchange="updateOrderStatus(${o.id}, this.value)" style="font-size:.75rem;padding:.2em .4em;background:var(--card2);border:1px solid var(--border);border-radius:5px;color:var(--text)">
        ${Object.entries(STATUS_LABELS).map(([val,[l]]) => `<option value="${val}" ${o.status===val?'selected':''}>${l}</option>`).join('')}
      </select></td>
      <td style="font-size:.78rem;color:var(--muted)">${fmtDate(o.created_at, true)}</td>
      <td style="white-space:nowrap">
        <button class="btn btn-secondary btn-xs" onclick="viewOrder(${o.id})">👁 Lihat</button>
        ${o.phone?`<a href="https://wa.me/${ph}" target="_blank" class="btn btn-secondary btn-xs">💬</a>`:''}
        <button class="btn btn-danger btn-xs" onclick="deleteOrder(${o.id})">🗑</button>
      </td>
    </tr>`;
  }).join('') || `<tr><td colspan="8" class="empty-state"><p>Tiada pesanan dijumpai.</p></td></tr>`;
}
function updateOrderStatus(id, status) {
  const o = DATA.orders.find(x => x.id === id);
  o.status = status; saveData(); showFlash('ok','Status pesanan dikemaskini.'); renderOrders();
}
function deleteOrder(id) {
  if (!confirm('Padam pesanan ini?')) return;
  DATA.orders = DATA.orders.filter(o => o.id !== id);
  saveData(); showFlash('ok','Pesanan dipadam.'); renderOrders();
}
function viewOrder(id) {
  const o = DATA.orders.find(x => x.id === id);
  if (!o) return;
  const [lbl, cls] = STATUS_LABELS[o.status] || ['—',''];
  const sizes = o.sizes || {};
  const sizeEntries = Object.entries(sizes).filter(([,q]) => q > 0);
  let ph = (o.phone||'').replace(/[^0-9]/g,''); if (ph.startsWith('0')) ph = '6'+ph;
  const waMsg = `Salam ${o.name}! Berkenaan pesanan custom jersey anda${o.design?` (${o.design})`:''}...`;

  document.getElementById('orderModalTitle').textContent = `📋 Detail Pesanan #${o.id}`;
  document.getElementById('orderModalBody').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
      <div><div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Pelanggan</div>
        <div style="font-weight:600">${esc(o.name)}</div><div style="font-size:.82rem;margin-top:.2rem">${esc(o.phone)}</div>
        ${o.team?`<div style="font-size:.82rem;color:var(--muted)">${esc(o.team)}</div>`:''}</div>
      <div><div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Tarikh</div>
        <div>${fmtDate(o.created_at, true)}</div><div style="margin-top:.5rem"><span class="badge ${cls}">${lbl}</span></div></div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:var(--card2);border-radius:8px;padding:.75rem;margin-top:1rem">
      <div><div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Kategori</div><div style="font-size:.84rem;font-weight:600">${esc(o.category||'—')}</div></div>
      <div><div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Jenis Baju</div><div style="font-size:.84rem;font-weight:600">${esc(o.jenis||'—')}</div></div>
      <div><div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Design</div><div style="font-size:.84rem">${esc(o.design||'—')}</div></div>
      <div><div style="font-size:.68rem;color:var(--muted);margin-bottom:.2rem">Jumlah Helai</div><div style="font-size:.84rem;font-weight:700;color:var(--green)">${o.total_qty||0} helai</div></div>
    </div>
    ${sizeEntries.length ? `<div style="margin-top:1rem"><div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.4rem">Saiz</div>
      <div style="display:flex;flex-wrap:wrap;gap:.4rem">${sizeEntries.map(([sz,q]) => `<span style="background:var(--card2);border:1px solid var(--border);border-radius:6px;padding:.25rem .65rem;font-size:.78rem"><strong>${esc(sz.toUpperCase())}</strong>: ${q}</span>`).join('')}</div></div>` : ''}
    ${o.custom_name ? `<div style="display:flex;align-items:center;gap:.5rem;font-size:.84rem;margin-top:1rem"><span style="color:var(--amber)">⚠</span> Ada nama / nombor pada baju</div>` : ''}
    ${o.notes ? `<div style="margin-top:1rem"><div style="font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem">Catatan</div><div style="background:var(--card2);border-radius:8px;padding:.75rem;font-size:.84rem;white-space:pre-wrap">${esc(o.notes)}</div></div>` : ''}
    <div style="display:flex;gap:.75rem;align-items:flex-end;margin-top:1.25rem">
      <div class="f-grp" style="flex:1;margin:0"><label>Kemaskini Status</label>
        <select id="orderDetailStatus">${Object.entries(STATUS_LABELS).map(([val,[l]]) => `<option value="${val}" ${o.status===val?'selected':''}>${l}</option>`).join('')}</select>
      </div>
      <button class="btn btn-primary" onclick="saveOrderStatusFromModal(${o.id})">Simpan</button>
    </div>
    <div style="display:flex;gap:.75rem;margin-top:.75rem">
      ${o.phone ? `<a href="https://wa.me/${ph}?text=${encodeURIComponent(waMsg)}" target="_blank" class="btn btn-primary" style="flex:1;justify-content:center">💬 WhatsApp Pelanggan</a>` : ''}
      <button class="btn btn-danger" onclick="deleteOrder(${o.id}); closeModal('orderModal')">🗑 Padam</button>
    </div>`;
  openModal('orderModal');
}
function saveOrderStatusFromModal(id) {
  const status = document.getElementById('orderDetailStatus').value;
  updateOrderStatus(id, status);
  closeModal('orderModal');
}
document.getElementById('orderSearch').addEventListener('input', function () { orderFilterQ = this.value.trim(); renderOrders(); });
document.getElementById('orderStatusFilter').addEventListener('change', function () { orderFilterStatus = this.value; renderOrders(); });
document.getElementById('btnOrderReset').addEventListener('click', () => { orderFilterQ=''; orderFilterStatus=''; document.getElementById('orderSearch').value=''; document.getElementById('orderStatusFilter').value=''; renderOrders(); });

/* ══════════════════════════════════════
   SETTINGS
══════════════════════════════════════ */
function renderSettings() {
  const s = DATA.settings;
  document.getElementById('s_wa_num').value = s.wa_num || '';
  document.getElementById('s_wa_display').value = s.wa_display || '';
  document.getElementById('s_site_name').value = s.site_name || '';
  document.getElementById('s_site_email').value = s.site_email || '';
  document.getElementById('s_min_order').value = s.min_order || '';
  document.getElementById('s_site_ig').value = s.site_ig || '';
  document.getElementById('s_site_fb').value = s.site_fb || '';
  document.getElementById('s_site_tt').value = s.site_tt || '';
  document.getElementById('s_hero_tagline').value = s.hero_tagline || '';
  document.getElementById('s_hero_sub').value = s.hero_sub || '';
  document.getElementById('waPreview').textContent = 'https://wa.me/' + (s.wa_num || '');
}
document.getElementById('s_wa_num').addEventListener('input', function () { document.getElementById('waPreview').textContent = 'https://wa.me/' + this.value; });
document.getElementById('settingsForm').addEventListener('submit', function (e) {
  e.preventDefault();
  DATA.settings = {
    wa_num: document.getElementById('s_wa_num').value.trim(),
    wa_display: document.getElementById('s_wa_display').value.trim(),
    site_name: document.getElementById('s_site_name').value.trim(),
    site_email: document.getElementById('s_site_email').value.trim(),
    min_order: document.getElementById('s_min_order').value.trim(),
    site_ig: document.getElementById('s_site_ig').value.trim(),
    site_fb: document.getElementById('s_site_fb').value.trim(),
    site_tt: document.getElementById('s_site_tt').value.trim(),
    hero_tagline: document.getElementById('s_hero_tagline').value.trim(),
    hero_sub: document.getElementById('s_hero_sub').value.trim(),
  };
  saveData();
  showFlash('ok', 'Tetapan berjaya disimpan.');
});

/* ══════════════════════════════════════
   USERS
══════════════════════════════════════ */
let editingUserId = null;

function renderUsers() {
  const me = currentUser();
  const users = [...DATA.users].sort((a,b) => a.role.localeCompare(b.role) || a.username.localeCompare(b.username));
  document.getElementById('usersCount').textContent = `Semua Pengguna (${users.length})`;
  document.getElementById('usersBody').innerHTML = users.map(u => `
    <tr>
      <td><div style="display:flex;align-items:center;gap:.6rem">
        <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;color:#fff;flex-shrink:0">${esc(u.username.charAt(0).toUpperCase())}</div>
        <div><div style="font-weight:600;font-size:.84rem">${esc(u.username)}</div>${u.id===me.id?`<div style="font-size:.7rem;color:var(--muted)">(Anda)</div>`:''}</div></div></td>
      <td style="font-size:.82rem">${esc(u.email)}</td>
      <td><span class="badge ${u.role==='superadmin'?'bg-amber':'bg-green'}">${u.role==='superadmin'?'👑 Superadmin':'Admin'}</span></td>
      <td>${u.id!==me.id ? `<label class="toggle"><input type="checkbox" ${u.active?'checked':''} onchange="toggleUser(${u.id})"><span class="tgl-sl"></span></label>` : `<span class="badge bg-green">Aktif</span>`}</td>
      <td style="white-space:nowrap">
        <button class="btn btn-secondary btn-xs" onclick="editUser(${u.id})">✏ Edit</button>
        ${u.id!==me.id ? `<button class="btn btn-danger btn-xs" onclick="deleteUser(${u.id})">🗑</button>` : ''}
      </td>
    </tr>`).join('');
}
function toggleUser(id) {
  const me = currentUser();
  if (id === me.id) { showFlash('err','Tidak boleh nyahaktif akaun sendiri.'); return; }
  const u = DATA.users.find(x => x.id === id);
  u.active = u.active ? 0 : 1;
  saveData(); showFlash('ok','Status dikemaskini.'); renderUsers();
}
function deleteUser(id) {
  const me = currentUser();
  if (id === me.id) { showFlash('err','Tidak boleh padam akaun sendiri.'); return; }
  if (!confirm('Padam pengguna ini?')) return;
  DATA.users = DATA.users.filter(u => u.id !== id);
  saveData(); showFlash('ok','Pengguna dipadam.'); renderUsers();
}
function editUser(id) {
  const u = DATA.users.find(x => x.id === id);
  editingUserId = id;
  document.getElementById('userFormTitle').textContent = '✏ Edit Pengguna';
  document.getElementById('userSubmitBtn').textContent = '💾 Simpan';
  document.getElementById('btnUserCancel').hidden = false;
  document.getElementById('userPassLabel').innerHTML = 'Password <span class="f-hint">(biarkan kosong jika tidak tukar)</span>';
  document.getElementById('userId').value = u.id;
  document.getElementById('userUsername').value = u.username;
  document.getElementById('userEmail').value = u.email;
  document.getElementById('userPassword').value = '';
  document.getElementById('userRole').value = u.role;
  document.getElementById('userActive').checked = !!u.active;
}
function resetUserForm() {
  editingUserId = null;
  document.getElementById('userFormTitle').textContent = '＋ Tambah Pengguna';
  document.getElementById('userSubmitBtn').textContent = '＋ Tambah Pengguna';
  document.getElementById('btnUserCancel').hidden = true;
  document.getElementById('userPassLabel').innerHTML = 'Password (min. 8 aksara)';
  document.getElementById('userForm').reset();
  document.getElementById('userId').value = '';
  document.getElementById('userActive').checked = true;
}
document.getElementById('btnUserCancel').addEventListener('click', resetUserForm);
document.getElementById('userForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const username = document.getElementById('userUsername').value.trim();
  const email = document.getElementById('userEmail').value.trim();
  const role = document.getElementById('userRole').value === 'superadmin' ? 'superadmin' : 'admin';
  const active = document.getElementById('userActive').checked ? 1 : 0;
  const password = document.getElementById('userPassword').value;
  if (!username || !email) { showFlash('err','Username dan email wajib diisi.'); return; }

  if (editingUserId) {
    const me = currentUser();
    if (editingUserId === me.id && role !== 'superadmin') { showFlash('err','Anda tidak boleh turunkan peranan akaun sendiri.'); return; }
    if (password && password.length < 8) { showFlash('err','Password min. 8 aksara.'); return; }
    const u = DATA.users.find(x => x.id === editingUserId);
    Object.assign(u, {username, email, role, active});
    if (password) u.password = password;
    showFlash('ok','Pengguna dikemaskini.');
  } else {
    if (!password || password.length < 8) { showFlash('err','Password min. 8 aksara wajib diisi untuk akaun baru.'); return; }
    if (DATA.users.some(u => u.username === username || u.email === email)) { showFlash('err','Username atau email sudah wujud.'); return; }
    DATA.users.push({id: nextId(DATA.users), username, email, password, role, active, last_login:null});
    showFlash('ok','Pengguna baru ditambah.');
  }
  saveData();
  resetUserForm();
  renderUsers();
});

/* ══════════════════════════════════════
   NAV / INIT
══════════════════════════════════════ */
document.querySelectorAll('[data-page]').forEach(el => el.addEventListener('click', () => showPage(el.dataset.page)));

document.getElementById('btnLogout').addEventListener('click', () => {
  clearSession();
  location.reload();
});

document.getElementById('loginForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const user = document.getElementById('loginUser').value.trim();
  const pass = document.getElementById('loginPass').value;
  document.getElementById('loginError').innerHTML = '';
  if (doLogin(user, pass)) {
    boot();
  } else {
    document.getElementById('loginError').innerHTML = '<div class="flash flash-err" style="margin-bottom:.85rem">Username atau password salah.</div>';
  }
});

function boot() {
  const u = currentUser();
  if (!u) {
    document.getElementById('loginScreen').hidden = false;
    document.getElementById('app').hidden = true;
    document.getElementById('app').style.display = 'none';
    return;
  }
  document.getElementById('loginScreen').hidden = true;
  document.getElementById('app').hidden = false;
  document.getElementById('app').style.display = 'flex';
  document.getElementById('sbAvatar').textContent = u.username.charAt(0).toUpperCase();
  document.getElementById('sbUname').textContent = u.username;
  document.getElementById('sbUrole').textContent = u.role;
  document.getElementById('topRole').textContent = u.role;
  document.getElementById('navUsers').hidden = u.role !== 'superadmin';

  const hashPage = location.hash.replace('#','');
  showPage(PAGE_TITLES[hashPage] ? hashPage : 'dashboard');
}

loadData();
boot();
