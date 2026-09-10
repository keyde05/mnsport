<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM mn_users WHERE id = ? AND active = 1');
$stmt->execute([$_SESSION['admin_id']]);
$adminUser = $stmt->fetch();

if (!$adminUser) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}
