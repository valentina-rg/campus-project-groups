<?php
// index.php
session_start();

// per ora niente DB, usiamo gruppi finti
$groups = [
    [
        'title' => 'Progetto Tecnologie Web',
        'course_name' => 'Tecnologie Web',
        'description' => 'Sito per la gestione dei gruppi elaborati del campus.',
        'deadline' => '2026-06-15',
        'max_members' => 4,
        'current_members' => 2,
        'status' => 'open',
    ],
    [
        'title' => 'Progetto Reti',
        'course_name' => 'Reti di Calcolatori',
        'description' => 'Gruppo per simulatore di routing e protocolli.',
        'deadline' => '2026-05-30',
        'max_members' => 5,
        'current_members' => 5,
        'status' => 'full',
    ],
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gruppi per Elaborati</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">ProjectGroups</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="group_new.php">Crea gruppo</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profilo</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Registrati</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">
    <h1 class="mb-4 h3">Gruppi per elaborati e progetti</h1>

    <div class="row g-3">
        <?php foreach ($groups as $g): ?>
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <?= htmlspecialchars($g['title']) ?>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?= htmlspecialchars($g['course_name']) ?>
                        </h6>
                        <p class="card-text">
                            <?= htmlspecialchars($g['description']) ?>
                        </p>
