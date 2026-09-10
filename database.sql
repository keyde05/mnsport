-- ══════════════════════════════════════════
--  MN SPORTS — Database Schema
--  Import fail ini dalam phpMyAdmin atau
--  jalankan: mysql -u root < database.sql
-- ══════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS mnsports
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE mnsports;

-- ── Kategori ──────────────────────────────
CREATE TABLE IF NOT EXISTS mn_categories (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  slug        VARCHAR(100)  NOT NULL UNIQUE,
  icon        VARCHAR(10)   DEFAULT '',
  description TEXT,
  sort_order  INT           DEFAULT 0,
  active      TINYINT(1)    DEFAULT 1,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Produk ────────────────────────────────
CREATE TABLE IF NOT EXISTS mn_products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT           DEFAULT NULL,
  code        VARCHAR(50)   DEFAULT '',
  name        VARCHAR(200)  NOT NULL,
  design      VARCHAR(200)  DEFAULT '',
  image       VARCHAR(255)  DEFAULT '',
  price       DECIMAL(10,2) DEFAULT 0,
  colors      VARCHAR(255)  DEFAULT '',
  description TEXT,
  badge       VARCHAR(50)   DEFAULT '',
  badge_class VARCHAR(50)   DEFAULT '',
  featured    TINYINT(1)    DEFAULT 0,
  active      TINYINT(1)    DEFAULT 1,
  sort_order  INT           DEFAULT 0,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES mn_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── Pengguna Admin ────────────────────────
CREATE TABLE IF NOT EXISTS mn_users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(100) NOT NULL UNIQUE,
  email         VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('superadmin','admin') DEFAULT 'admin',
  last_login    TIMESTAMP    NULL,
  active        TINYINT(1)   DEFAULT 1,
  created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Tetapan ───────────────────────────────
CREATE TABLE IF NOT EXISTS mn_settings (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  `key`      VARCHAR(100) NOT NULL UNIQUE,
  value      TEXT,
  label      VARCHAR(200),
  updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Pesanan (dari borang order) ───────────
CREATE TABLE IF NOT EXISTS mn_orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(200),
  phone       VARCHAR(50),
  team        VARCHAR(200),
  category    VARCHAR(100),
  design      VARCHAR(200),
  jenis       VARCHAR(100),
  sizes_json  TEXT,
  total_qty   INT          DEFAULT 0,
  custom_name TINYINT(1)   DEFAULT 0,
  notes       TEXT,
  status      ENUM('baru','dihubungi','proses','siap','batal') DEFAULT 'baru',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ══════════════════════════════════════════
--  SEED DATA
-- ══════════════════════════════════════════

-- Kategori
INSERT IGNORE INTO mn_categories (name, slug, icon, description, sort_order) VALUES
('Football', 'football', '⚽', 'Jersey bola sepak & sukan sublimasi premium', 1),
('Casual',   'casual',   '👕', 'Polo & jersey santai untuk semua majlis',     2),
('Formal',   'formal',   '👔', 'Uniform korporat & jabatan rasmi',            3);

-- Tetapan
INSERT IGNORE INTO mn_settings (`key`, value, label) VALUES
('wa_num',     '60123456789',                    'Nombor WhatsApp (tanpa +)'),
('wa_display', '+60 12-345 6789',                'Paparan Nombor WhatsApp'),
('site_email', 'info@mnsports.my',               'Email Syarikat'),
('site_name',  'MN Sports',                      'Nama Syarikat'),
('site_ig',    'https://instagram.com/mnsports', 'URL Instagram'),
('site_fb',    '',                               'URL Facebook'),
('site_tt',    '',                               'URL TikTok'),
('min_order',  '10',                             'Minimum Order (helai)'),
('hero_tagline','JERSI SUBLIMASI TERBAIK',         'Hero Tagline'),
('hero_sub',   'Kualiti premium, harga berpatutan','Hero Sub Tagline');

-- Produk (sama seperti dalam main.js)
INSERT IGNORE INTO mn_products (category_id, code, name, design, image, price, colors, description, badge, badge_class, featured) VALUES
(2, 'p000',  'Classic Polo Pro',  'Design #000',     'baju/000.jpg',   55, 'Hitam / Maroon / Oren',  'Polo sublimasi premium dengan corak diagonal dinamik. Sesuai untuk acara kasual dan uniform syarikat.', 'Popular',    '',     1),
(1, 'p093',  'Brush Art Jersey',  'Design #093',     'baju/093.jpg',   48, 'Hitam / Pink',           'Jersey dengan corak brush stroke yang energetik. Pilihan popular untuk pasukan bola sepak aktif.',     'New',        'dark', 0),
(3, 'p103',  'Corporate Marine',  'Design #103',     'baju/103.jpg',   55, 'Merah Gelap / Emas',     'Jersey korporat premium dengan ilustrasi industri yang unik. Terbaik untuk baju uniform syarikat.',     '',           '',     0),
(3, 'p106',  'Royal Baroque',     'Design #106',     'baju/106.jpg',   52, 'Hitam / Emas',           'Jersey eksklusif dengan corak baroque mewah berwarna emas. Untuk pasukan yang mahukan gaya tersendiri.','Exclusive',  'gold', 1),
(1, 'p106n9','Football Strike',   'Design #106 N9',  'baju/106n9.jpg', 45, 'Kuning / Hitam / Merah', 'Jersey bola sepak gaya bersih dengan warna terang. Ideal untuk pasukan bola sepak tempatan.',           'Bestseller', '',     1),
(1, 'p108',  'Ocean Splash',      'Design #108',     'baju/108.jpg',   48, 'Biru / Hijau / Kuning',  'Jersey sukan dengan corak percikan warna cerah. Sesuai untuk pelbagai sukan outdoor dan indoor.',       '',           '',     0),
(2, 'p109',  'Geometric Fade',    'Design #109',     'baju/109.jpg',   55, 'Pink / Ungu',            'Polo dengan corak geometrik gradient yang cantik. Popular untuk majlis dan uniform institusi.',          'Popular',    '',     1),
(1, 'phtjs', 'Teal Marble',       'Design HTJS',     'baju/htjs.jpg',  48, 'Teal / Hitam',           'Jersey dengan corak marble mewah berwarna teal. Rekaan unik untuk pasukan netball dan sukan indoor.',   '',           '',     0),
(3, 'pik',   'Heritage Polo',     'Design IK',       'baju/ik.jpg',    55, 'Putih / Merah / Oren',   'Polo heritage dengan corak geometrik bold. Sesuai untuk uniform jabatan kerajaan dan korporat.',        '',           '',     0),
(2, 'psgmc', 'Speed Racer',       'Design SGMC3',    'baju/sgmc.jpg',  52, 'Hitam / Multi-warna',    'Jersey bertenaga tinggi dengan corak brush multi-warna. Kegemaran komuniti sukan dan motor.',            'Hot',        '',     0);

-- ══════════════════════════════════════════
--  NOTA: Akaun superadmin dibuat melalui
--  http://localhost/MNsport/admin/setup.php
-- ══════════════════════════════════════════
