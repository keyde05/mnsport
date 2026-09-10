<?php
if (!isset($GLOBALS['mns_db'])) {
    try {
        $GLOBALS['mns_db'] = new PDO(
            'mysql:host=localhost;dbname=mnsports;charset=utf8mb4',
            'root', '',
            [PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
             PDO::ATTR_EMULATE_PREPARES   => false]
        );
    } catch (Exception $e) {
        $GLOBALS['mns_db'] = null;
    }
}
