<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mnsports');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES => false]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('<div style="font-family:sans-serif;padding:2rem;color:#f87171;background:#111;min-height:100vh">
        <h2>&#x26A0; Database Error</h2>
        <p style="margin-top:.5rem;color:#aaa">' . htmlspecialchars($e->getMessage()) . '</p>
        <p style="margin-top:1rem;color:#666;font-size:.85rem">
          Pastikan:<br>
          1. MySQL sedang berjalan di Laragon<br>
          2. Database <strong style="color:#e63012">mnsports</strong> telah dibuat<br>
          3. Import <strong>database.sql</strong> dalam phpMyAdmin
        </p>
    </div>');
}
