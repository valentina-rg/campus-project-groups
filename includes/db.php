<?php
// includes/db.php

$DB_HOST = 'localhost';
$DB_NAME = 'campus_project_groups';
$DB_USER = 'root';
$DB_PASS = ''; // metti qui la tua password se ce l'hai

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Errore connessione DB: ' . $e->getMessage());
}