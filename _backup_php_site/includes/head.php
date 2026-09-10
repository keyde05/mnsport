<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($desc ?? 'MN Sports — Premium Custom Jersey Malaysia') ?>">
  <title><?= htmlspecialchars($title ?? 'MN Sports') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
  <link rel="icon" href="logo/logo.jpg" type="image/jpeg">
  <link rel="stylesheet" href="css/style.css">
</head>
