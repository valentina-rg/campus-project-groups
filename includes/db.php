<?php
// includes/db.php

$config = require __DIR__ . '/config.local.php';

$DB_HOST = $config['host'];
$DB_PORT = $config['port'];
$DB_NAME = $config['dbname'];
$DB_USER = $config['user'];
$DB_PASS = $config['pass'];

try {
    $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Errore connessione DB: ' . $e->getMessage());
}