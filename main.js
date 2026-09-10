/* ═══════════════════════════════════════════════════
   MN SPORTS — main.js  (single-page, fully static)
   Shared: Preloader · Cursor · Navbar · Router · Cart · Modal · Toast
   Home:   Hero Canvas · Counter · Featured Products
   Products: Filter (Football / Casual / Formal)
   Order:  Process · Custom Form → WhatsApp
   About:  Testimonials
═══════════════════════════════════════════════════ */

'use strict';

/* ── CONFIG ── (tukar nilai ini untuk konfigurasi laman) */
const CONFIG = {
  waNum:      '60123456789',
  waDisplay:  '+60 12-345 6789',
  siteEmail:  'info@mnsports.my',
  siteName:   'MN Sports',
  siteIG:     'https://instagram.com/mnsports',
  siteFB:     '',
  siteTT:     '',
  minOrder:   10,
};

const CAT_LABEL = { football:'Football', casual:'Casual', formal:'Formal' };

/* ── PRODUCTS (statik — sama data seperti database.sql) ── */
const PRODUCTS = [
  { id:'p000',   name:'Classic Polo Pro', design:'Design #000',    image:'baju/000.jpg',   category:'casual',   price:55, colors:'Hitam / Maroon / Oren',  desc:'Polo sublimasi premium dengan corak diagonal dinamik. Sesuai untuk acara kasual dan uniform syarikat.', badge:'Popular',    badgeClass:'',     featured:true  },
  { id:'p093',   name:'Brush Art Jersey', design:'Design #093',    image:'baju/093.jpg',   category:'football', price:48, colors:'Hitam / Pink',           desc:'Jersey dengan corak brush stroke yang energetik. Pilihan popular untuk pasukan bola sepak aktif.',      badge:'New',        badgeClass:'dark', featured:false },
  { id:'p103',   name:'Corporate Marine', design:'Design #103',    image:'baju/103.jpg',   category:'formal',   price:55, colors:'Merah Gelap / Emas',     desc:'Jersey korporat premium dengan ilustrasi industri yang unik. Terbaik untuk baju uniform syarikat.',     badge:'',           badgeClass:'',     featured:false },
  { id:'p106',   name:'Royal Baroque',    design:'Design #106',    image:'baju/106.jpg',   category:'formal',   price:52, colors:'Hitam / Emas',           desc:'Jersey eksklusif dengan corak baroque mewah berwarna emas. Untuk pasukan yang mahukan gaya tersendiri.',badge:'Exclusive',  badgeClass:'gold', featured:true  },
  { id:'p106n9', name:'Football Strike',  design:'Design #106 N9', image:'baju/106n9.jpg', category:'football', price:45, colors:'Kuning / Hitam / Merah', desc:'Jersey bola sepak gaya bersih dengan warna terang. Ideal untuk pasukan bola sepak tempatan.',           badge:'Bestseller', badgeClass:'',     featured:true  },
  { id:'p108',   name:'Ocean Splash',     design:'Design #108',    image:'baju/108.jpg',   category:'football', price:48, colors:'Biru / Hijau / Kuning',  desc:'Jersey sukan dengan corak percikan warna cerah. Sesuai untuk pelbagai sukan outdoor dan indoor.',        badge:'',           badgeClass:'',     featured:false },
  { id:'p109',   name:'Geometric Fade',   design:'Design #109',    image:'baju/109.jpg',   category:'casual',   price:55, colors:'Pink / Ungu',            desc:'Polo dengan corak geometrik gradient yang cantik. Popular untuk majlis dan uniform institusi.',         badge:'Popular',    badgeClass:'',     featured:true  },
  { id:'phtjs',  name:'Teal Marble',      design:'Design HTJS',    image:'baju/htjs.jpg',  category:'football', price:48, colors:'Teal / Hitam',           desc:'Jersey dengan corak marble mewah berwarna teal. Rekaan unik untuk pasukan netball dan sukan indoor.',   badge:'',           badgeClass:'',     featured:false },
  { id:'pik',    name:'Heritage Polo',    design:'Design IK',      image:'baju/ik.jpg',    category:'formal',   price:55, colors:'Putih / Merah / Oren',   desc:'Polo heritage dengan corak geometrik bold. Sesuai untuk uniform jabatan kerajaan dan korporat.',         badge:'',           badgeClass:'',     featured:false },
  { id:'psgmc',  name:'Speed Racer',      design:'Design SGMC3',   image:'baju/sgmc.jpg',  category:'casual',   price:52, colors:'Hitam / Multi-warna',    desc:'Jersey bertenaga tinggi dengan corak brush multi-warna. Kegemaran komuniti sukan dan motor.',           badge:'Hot',        badgeClass:'',     featured:false },
];

const TESTIMONIALS = [
  { stars:5, text:'"Jersi MN Sports memang lain dari yang lain! Kualiti sublimasi sangat tajam, warna tidak luntur langsung selepas berkali-kali basuh. Pasukan kami sangat puas hati!"', author:'Ahmad Faizal', role:'Kapten KKSB FC' },
  { stars:5, text:'"Kami order untuk 45 orang staff syarikat dan hasilnya memukau. Proses order pun mudah, team MN Sports sangat membantu dari A sampai Z."', author:'Puan Rohayu', role:'HR Manager, COC Marine Sdn Bhd' },
  { stars:5, text:'"Design HTJS Netball Team kami dihasilkan oleh MN Sports. Cantik, kemas, dan siap tepat pada masa. Definitely akan order lagi!"', author:'Nurul Ain', role:'Kapten HTJS Netball Team' },
  { stars:5, text:'"Percaya atau tidak, ini order pertama saya dengan MN Sports dan terus jadi langganan tetap! Service cepat, harga berpatutan, kualiti premium."', author:'Encik Rashdan', role:'Pengurus Aerodance Team' },
];

/* ── STATE ── */
let cart = JSON.parse(localStorage.getItem('mns_cart') || '[]');
let testiIdx = 0;

/* ═══════════════════════════════════
   ROUTER
═══════════════════════════════════ */
const ROUTES = ['home','football','casual','formal','products','order','about','contact'];

function currentRoute() {
  const h = location.hash.replace('#', '');
  return ROUTES.includes(h) ? h : 'home';
}

function showRoute(route) {
  ROUTES.forEach(r => {
    const el = document.getElementById('page-' + r);
    if (el) el.hidden = (r !== route);
  });
  updateActiveNav(route);
  window.scrollTo({ top: 0 });
  document.body.classList.remove('menu-open');
  const mm = document.getElementById('mobMenu');
  if (mm) mm.classList.remove('open');
}

function updateActiveNav(route) {
  document.querySelectorAll('.nav-links a[data-route], .mob-lnk[data-route]').forEach(a => {
    a.classList.toggle('active', a.dataset.route === route);
  });
  document.querySelectorAll('[data-route-group]').forEach(a => {
    a.classList.toggle('active', a.dataset.routeGroup.split(',').includes(route));
  });
}

window.addEventListener('hashchange', () => showRoute(currentRoute()));

/* ═══════════════════════════════════
   CONFIG → DOM
═══════════════════════════════════ */
function applyConfig() {
  document.querySelectorAll('[data-cfg="wa-url"]').forEach(el => { el.href = 'https://wa.me/' + CONFIG.waNum; });
  document.querySelectorAll('[data-cfg-text="wa-display"]').forEach(el => { el.textContent = CONFIG.waDisplay; });
  document.querySelectorAll('[data-cfg-text="email"]').forEach(el => { el.textContent = CONFIG.siteEmail; });
  document.querySelectorAll('[data-cfg="mailto"]').forEach(el => { el.href = 'mailto:' + CONFIG.siteEmail; el.textContent = CONFIG.siteEmail; });
  document.querySelectorAll('[data-cfg="ig-url"]').forEach(el => { el.href = CONFIG.siteIG; });
  document.querySelectorAll('[data-cfg="fb-url"]').forEach(el => { if (CONFIG.siteFB) el.href = CONFIG.siteFB; else el.style.display = 'none'; });
  document.querySelectorAll('[data-cfg="tt-url"]').forEach(el => { if (CONFIG.siteTT) el.href = CONFIG.siteTT; else el.style.display = 'none'; });
  document.querySelectorAll('[data-cfg="min-order"]').forEach(el => { el.textContent = CONFIG.minOrder; });
  const wa = document.querySelector('a[data-cfg="wa-url"].cc-lnk');
  if (wa) wa.textContent = CONFIG.waDisplay;
  const yr = document.getElementById('curYear');
  if (yr) yr.textContent = new Date().getFullYear();
}

/* ═══════════════════════════════════
   PRELOADER / PAGE INIT
═══════════════════════════════════ */
(function preloader() {
  const fill = document.getElementById('plFill');
  const pct  = document.getElementById('plPct');
  const el   = document.getElementById('preloader');

  function afterLoad() {
    document.body.classList.remove('is-loading');
    applyConfig();
    initHeroCanvas();
    startCounters();
    updateCategoryCounts();
    renderFeatured();
    renderGrid('footballGrid', 'football');
    renderGrid('casualGrid', 'casual');
    renderGrid('formalGrid', 'formal');
    renderGrid('allProductsGrid', 'all');
    initScrollReveal();
    showRoute(currentRoute());
  }

  if (!el) { afterLoad(); return; }

  let p = 0;
  const iv = setInterval(() => {
    p += Math.random() * 18 + 5;
    if (p >= 100) {
      p = 100; clearInterval(iv);
      setTimeout(() => { el.classList.add('done'); afterLoad(); }, 300);
    }
    if (fill) fill.style.width = p + '%';
    if (pct)  pct.textContent  = Math.floor(p) + '%';
  }, 80);
})();

/* ═══════════════════════════════════
   CATEGORY COUNTS
═══════════════════════════════════ */
function updateCategoryCounts() {
  const counts = { football:0, casual:0, formal:0 };
  PRODUCTS.forEach(p => { if (counts[p.category] !== undefined) counts[p.category]++; });
  const fb = document.getElementById('catCountFootball'); if (fb) fb.textContent = counts.football + ' Design';
  const cs = document.getElementById('catCountCasual');   if (cs) cs.textContent = counts.casual + ' Design';
  const fm = document.getElementById('catCountFormal');   if (fm) fm.textContent = counts.formal + ' Design';
  const tp = document.getElementById('totalProdCount');   if (tp) tp.textContent = PRODUCTS.length;
  const va = document.getElementById('viewAllBtn');       if (va) va.textContent = `Lihat Semua ${PRODUCTS.length} Koleksi →`;
}

/* ═══════════════════════════════════
   CUSTOM CURSOR
═══════════════════════════════════ */
(function cursor() {
  const dot  = document.getElementById('cDot');
  const ring = document.getElementById('cRing');
  if (!dot || window.matchMedia('(hover:none)').matches) return;

  let mx = 0, my = 0, rx = 0, ry = 0;
  document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
  dot.style.position = 'fixed';
  ring.style.position = 'fixed';

  function tick() {
    dot.style.left  = mx + 'px';
    dot.style.top   = my + 'px';
    rx += (mx - rx) * 0.14;
    ry += (my - ry) * 0.14;
    ring.style.left = rx + 'px';
    ring.style.top  = ry + 'px';
    requestAnimationFrame(tick);
  }
  tick();

  document.addEventListener('mouseover', e => {
    if (e.target.closest('a,button,.p-card')) ring.classList.add('hovered');
  });
  document.addEventListener('mouseout', e => {
    if (e.target.closest('a,button,.p-card')) ring.classList.remove('hovered');
  });
})();

/* ═══════════════════════════════════
   NAVBAR
═══════════════════════════════════ */
(function navbar() {
  const nav    = document.getElementById('navbar');
  const burger = document.getElementById('burger');
  const menu   = document.getElementById('mobMenu');
  const links  = document.querySelectorAll('.mob-lnk');
  const stBtn  = document.getElementById('scrollTop');

  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 60);
    if (stBtn) stBtn.classList.toggle('show', window.scrollY > 400);
  }, { passive:true });

  burger.addEventListener('click', () => {
    document.body.classList.toggle('menu-open');
    menu.classList.toggle('open');
  });
  links.forEach(l => l.addEventListener('click', () => {
    document.body.classList.remove('menu-open');
    menu.classList.remove('open');
  }));
})();

/* ═══════════════════════════════════
   HERO CANVAS
═══════════════════════════════════ */
function initHeroCanvas() {
  const canvas = document.getElementById('heroCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let W, H, particles = [];

  function resize() {
    W = canvas.width  = canvas.offsetWidth;
    H = canvas.height = canvas.offsetHeight;
  }
  resize();
  window.addEventListener('resize', resize, { passive:true });

  class Particle {
    constructor() { this.reset(true); }
    reset(initial) {
      this.x  = Math.random() * W;
      this.y  = initial ? Math.random() * H : H + 10;
      this.vx = (Math.random() - 0.5) * 0.4;
      this.vy = -(Math.random() * 0.5 + 0.1);
      this.r  = Math.random() * 1.5 + 0.5;
      this.a  = 0;
      this.life = Math.random() * 200 + 100;
      this.age  = initial ? Math.floor(Math.random() * this.life) : 0;
    }
    update() {
      this.x += this.vx; this.y += this.vy; this.age++;
      const half = this.life / 2;
      this.a = this.age < half ? this.age / half : 1 - (this.age - half) / half;
      if (this.age >= this.life || this.y < -10) this.reset(false);
    }
    draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(230,48,18,${this.a * 0.7})`;
      ctx.fill();
    }
  }

  const COUNT = Math.min(80, Math.floor(W * H / 8000));
  for (let i = 0; i < COUNT; i++) particles.push(new Particle());

  function drawLines() {
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const p1 = particles[i], p2 = particles[j];
        const dx = p1.x - p2.x, dy = p1.y - p2.y;
        const d  = Math.sqrt(dx*dx + dy*dy);
        if (d < 130) {
          ctx.beginPath();
          ctx.moveTo(p1.x, p1.y); ctx.lineTo(p2.x, p2.y);
          const alpha = (1 - d/130) * Math.min(p1.a, p2.a) * 0.3;
          ctx.strokeStyle = `rgba(230,48,18,${alpha})`;
          ctx.lineWidth = 0.5;
          ctx.stroke();
        }
      }
    }
  }

  function frame() {
    ctx.clearRect(0, 0, W, H);
    particles.forEach(p => { p.update(); p.draw(); });
    drawLines();
    requestAnimationFrame(frame);
  }
  frame();
}

/* ═══════════════════════════════════
   COUNTER ANIMATION
═══════════════════════════════════ */
function startCounters() {
  document.querySelectorAll('.ctr').forEach(el => {
    const target = parseInt(el.dataset.t, 10);
    let current  = 0;
    const step   = target / 60;
    const iv = setInterval(() => {
      current += step;
      if (current >= target) { current = target; clearInterval(iv); }
      el.textContent = Math.floor(current).toLocaleString();
    }, 25);
  });
}

/* ═══════════════════════════════════
   SCROLL REVEAL
═══════════════════════════════════ */
function initScrollReveal() {
  const els = document.querySelectorAll('.rv,.rv-left,.rv-right');
  const io  = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold:0.08, rootMargin:'0px 0px -30px 0px' });
  els.forEach(el => io.observe(el));
}

/* ═══════════════════════════════════
   CARD BUILDER (shared)
═══════════════════════════════════ */
function buildCard(p, i, delayBase) {
  const card = document.createElement('div');
  card.className = 'p-card rv';
  card.dataset.cat = p.category;
  card.dataset.id  = p.id;
  card.style.transitionDelay = (i * (delayBase || 0.07)) + 's';
  card.innerHTML = `
    <div class="p-img-wrap">
      <img src="${p.image}" alt="${p.name}" loading="lazy">
      ${p.badge ? `<span class="p-badge${p.badgeClass ? ' '+p.badgeClass : ''}">${p.badge}</span>` : ''}
      <div class="p-quick">Quick View</div>
    </div>
    <div class="p-info">
      <div class="p-cat-tag">${CAT_LABEL[p.category] || p.category}</div>
      <div class="p-design">${p.design}</div>
      <div class="p-name">${p.name}</div>
      <div class="p-colors">${p.colors}</div>
      <div class="p-foot">
        <div class="p-price">RM ${p.price}<span>/ helai</span></div>
        <button class="p-add" data-id="${p.id}">+ Minat</button>
      </div>
    </div>`;
  card.querySelector('.p-quick').addEventListener('click', e => { e.stopPropagation(); openModal(p); });
  card.querySelector('.p-add').addEventListener('click',  e => { e.stopPropagation(); addToCart(p); });
  card.addEventListener('click', () => openModal(p));
  return card;
}

/* ═══════════════════════════════════
   PRODUCT GRIDS
═══════════════════════════════════ */
function renderFeatured() {
  const grid = document.getElementById('featuredGrid');
  if (!grid) return;
  grid.innerHTML = '';
  PRODUCTS.filter(p => p.featured).forEach((p, i) => grid.appendChild(buildCard(p, i, 0.08)));
}

function renderGrid(gridId, category) {
  const grid = document.getElementById(gridId);
  if (!grid) return;
  grid.innerHTML = '';
  const list = category === 'all' ? PRODUCTS : PRODUCTS.filter(p => p.category === category);
  list.forEach((p, i) => grid.appendChild(buildCard(p, i % 4, 0.07)));
}

document.querySelectorAll('.f-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.f-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterAllProducts(btn.dataset.filter);
  });
});

function filterAllProducts(filter) {
  document.querySelectorAll('#allProductsGrid .p-card').forEach(card => {
    const match = filter === 'all' || card.dataset.cat === filter;
    if (match) {
      card.classList.remove('hidden');
      requestAnimationFrame(() => card.classList.add('in'));
    } else {
      card.classList.add('hidden');
      card.classList.remove('in');
    }
  });
}

/* ═══════════════════════════════════
   CART
═══════════════════════════════════ */
function saveCart() { localStorage.setItem('mns_cart', JSON.stringify(cart)); }

function addToCart(product) {
  const existing = cart.find(i => i.id === product.id);
  if (existing) { existing.qty++; }
  else { cart.push({ id:product.id, name:product.name, design:product.design, image:product.image, price:product.price, qty:1 }); }
  saveCart(); renderCart(); updateCartBadge();
  showToast(`✓ ${product.name} ditambah ke troli`);
  openCart();
}

function removeFromCart(id) {
  cart = cart.filter(i => i.id !== id);
  saveCart(); renderCart(); updateCartBadge();
}

function updateCartBadge() {
  const count = cart.reduce((s, i) => s + i.qty, 0);
  const badge = document.getElementById('cartCount');
  if (!badge) return;
  badge.textContent = count;
  badge.classList.toggle('show', count > 0);
}

function renderCart() {
  const items = document.getElementById('csItems');
  const empty = document.getElementById('csEmpty');
  const foot  = document.getElementById('csFoot');
  const total = document.getElementById('csTotal');
  if (!items) return;

  items.innerHTML = '';
  if (cart.length === 0) {
    empty.style.display = 'flex';
    foot.style.display  = 'none';
    return;
  }
  empty.style.display = 'none';
  foot.style.display  = 'block';

  let sum = 0;
  cart.forEach(item => {
    sum += item.price * item.qty;
    const div = document.createElement('div');
    div.className = 'ci';
    div.innerHTML = `
      <div class="ci-img"><img src="${item.image}" alt="${item.name}"></div>
      <div class="ci-info">
        <div class="ci-name">${item.name}</div>
        <div class="ci-design">${item.design}</div>
        <div class="ci-price">RM ${item.price} × ${item.qty} = RM ${item.price * item.qty}</div>
      </div>
      <button class="ci-rm" data-id="${item.id}">Buang</button>`;
    div.querySelector('.ci-rm').addEventListener('click', () => removeFromCart(item.id));
    items.appendChild(div);
  });
  total.textContent = 'RM ' + sum;
}

function openCart() {
  document.getElementById('cartSidebar').classList.add('open');
  document.getElementById('cartOverlay').classList.add('open');
}
function closeCart() {
  document.getElementById('cartSidebar').classList.remove('open');
  document.getElementById('cartOverlay').classList.remove('open');
}

const _cartBtn  = document.getElementById('cartBtn');
const _csClose  = document.getElementById('csClose');
const _cartOv   = document.getElementById('cartOverlay');
const _checkout = document.getElementById('checkoutBtn');

if (_cartBtn)  _cartBtn.addEventListener('click', openCart);
if (_csClose)  _csClose.addEventListener('click', closeCart);
if (_cartOv)   _cartOv.addEventListener('click', closeCart);
if (_checkout) _checkout.addEventListener('click', () => {
  if (cart.length === 0) { showToast('Troli anda kosong!'); return; }
  let msg = '🛒 *Pertanyaan Order — MN Sports*\n\nSalam, saya berminat dengan design berikut:\n\n';
  cart.forEach(item => { msg += `✦ *${item.name}* (${item.design})\n  - Kuantiti: ${item.qty} helai\n  - Harga anggaran: RM ${item.price * item.qty}\n\n`; });
  const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
  msg += `*Anggaran Jumlah: RM ${total}*\n\nBoleh berikan maklumat lanjut tentang cara order? Terima kasih 🙏`;
  window.open(`https://wa.me/${CONFIG.waNum}?text=${encodeURIComponent(msg)}`, '_blank');
});

updateCartBadge();
renderCart();

/* ═══════════════════════════════════
   PRODUCT MODAL
═══════════════════════════════════ */
function openModal(product) {
  const modal = document.getElementById('prodModal');
  const ov    = document.getElementById('modalOv');
  const inner = document.getElementById('pmInner');
  if (!modal) return;

  const waText = encodeURIComponent(`Salam, saya berminat dengan ${product.name} (${product.design}). Boleh berikan maklumat lanjut? Terima kasih!`);
  inner.innerHTML = `
    <div class="pm-img"><img src="${product.image}" alt="${product.name}"></div>
    <div class="pm-info">
      <div class="pm-tag">${product.design} · ${CAT_LABEL[product.category] || product.category}</div>
      <div class="pm-name">${product.name}</div>
      <div class="pm-price-row">
        <span class="pm-price">RM ${product.price}</span>
        <span class="pm-min">/ helai (min. ${CONFIG.minOrder} helai)</span>
      </div>
      <div class="pm-desc">${product.desc}</div>
      <div class="pm-colors"><strong>Warna: </strong>${product.colors}</div>
      <div class="pm-ctas">
        <button class="btn-primary pm-cart-btn">+ Tambah ke Minat</button>
        <a href="https://wa.me/${CONFIG.waNum}?text=${waText}" target="_blank" rel="noopener" class="btn-wa-sm">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Tanya via WA
        </a>
      </div>
    </div>`;

  inner.querySelector('.pm-cart-btn').addEventListener('click', () => { addToCart(product); closeModal(); });
  modal.classList.add('open');
  ov.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  const modal = document.getElementById('prodModal');
  const ov    = document.getElementById('modalOv');
  if (!modal) return;
  modal.classList.remove('open');
  ov.classList.remove('open');
  document.body.style.overflow = '';
}

const _pmClose = document.getElementById('pmClose');
const _modalOv = document.getElementById('modalOv');
if (_pmClose) _pmClose.addEventListener('click', closeModal);
if (_modalOv) _modalOv.addEventListener('click', closeModal);

/* ═══════════════════════════════════
   TESTIMONIALS
═══════════════════════════════════ */
(function testimonials() {
  const track = document.getElementById('testiTrack');
  const dots  = document.getElementById('testiDots');
  if (!track) return;

  TESTIMONIALS.forEach((t, i) => {
    const card = document.createElement('div');
    card.className = 't-card';
    card.innerHTML = `
      <div class="t-stars">${'★'.repeat(t.stars)}</div>
      <p class="t-text">${t.text}</p>
      <div class="t-author">${t.author}</div>
      <div class="t-role">${t.role}</div>`;
    track.appendChild(card);

    const dot = document.createElement('div');
    dot.className = 't-dot' + (i === 0 ? ' active' : '');
    dot.addEventListener('click', () => goTo(i));
    dots.appendChild(dot);
  });

  function goTo(idx) {
    testiIdx = idx;
    track.style.transform = `translateX(-${idx * 100}%)`;
    document.querySelectorAll('.t-dot').forEach((d, i) => d.classList.toggle('active', i === idx));
  }

  document.getElementById('tPrev').addEventListener('click', () => goTo((testiIdx - 1 + TESTIMONIALS.length) % TESTIMONIALS.length));
  document.getElementById('tNext').addEventListener('click', () => goTo((testiIdx + 1) % TESTIMONIALS.length));

  let tsX = 0;
  track.addEventListener('touchstart', e => { tsX = e.touches[0].clientX; }, { passive:true });
  track.addEventListener('touchend',   e => {
    const dx = tsX - e.changedTouches[0].clientX;
    if (Math.abs(dx) > 40) goTo(dx > 0
      ? (testiIdx + 1) % TESTIMONIALS.length
      : (testiIdx - 1 + TESTIMONIALS.length) % TESTIMONIALS.length);
  });

  setInterval(() => goTo((testiIdx + 1) % TESTIMONIALS.length), 5500);
})();

/* ═══════════════════════════════════
   ORDER FORM
═══════════════════════════════════ */
(function orderForm() {
  const form     = document.getElementById('orderForm');
  const qtyTotal = document.getElementById('qtyTotal');
  if (!form) return;

  form.querySelectorAll('.qin').forEach(inp => {
    inp.addEventListener('input', () => {
      let total = 0;
      form.querySelectorAll('.qin').forEach(q => { total += parseInt(q.value || 0, 10); });
      if (qtyTotal) {
        qtyTotal.textContent = total;
        qtyTotal.style.color = total < CONFIG.minOrder ? '#e63012' : '#25d366';
      }
    });
  });

  form.addEventListener('submit', e => {
    e.preventDefault();
    const d = Object.fromEntries(new FormData(form));
    if (!d.nama.trim())    { showToast('Sila masukkan nama anda'); return; }
    if (!d.telefon.trim()) { showToast('Sila masukkan nombor telefon'); return; }

    const sizes = ['xs','s','m','l','xl','xxl','xxxl'];
    let totalQty = 0, sizeStr = '';
    sizes.forEach(sz => {
      const v = parseInt(d[sz] || 0, 10);
      if (v > 0) { sizeStr += `  - ${sz.toUpperCase()}: ${v} helai\n`; totalQty += v; }
    });

    let msg = `🛒 *Custom Order — MN Sports*\n\n`;
    msg += `👤 *Nama:* ${d.nama}\n`;
    msg += `📱 *Telefon:* ${d.telefon}\n`;
    if (d.pasukan) msg += `🏆 *Pasukan/Org:* ${d.pasukan}\n`;
    msg += `\n👕 *Kategori:* ${d.kategori || 'Belum dipilih'}\n`;
    msg += `🎨 *Design:* ${d.design || 'Belum dipilih'}\n`;
    msg += `🧥 *Jenis:* ${d.jenis || 'Belum dipilih'}\n`;
    if (sizeStr) {
      msg += `\n📏 *Saiz & Kuantiti:*\n${sizeStr}`;
      msg += `📦 *Jumlah: ${totalQty} helai*\n`;
    }
    msg += `\n✏️ *Nama/Nombor pada baju:* ${d.customName === 'ya' ? 'Ya' : 'Tidak'}\n`;
    if (d.catatan) msg += `\n💬 *Catatan:* ${d.catatan}\n`;
    msg += `\nTerima kasih! Sila hubungi saya 🙏`;
    window.open(`https://wa.me/${CONFIG.waNum}?text=${encodeURIComponent(msg)}`, '_blank');
  });
})();

/* ═══════════════════════════════════
   TOAST
═══════════════════════════════════ */
function showToast(msg) {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2600);
}

/* ═══════════════════════════════════
   SCROLL TO TOP
═══════════════════════════════════ */
const _stBtn = document.getElementById('scrollTop');
if (_stBtn) _stBtn.addEventListener('click', () => window.scrollTo({ top:0, behavior:'smooth' }));
