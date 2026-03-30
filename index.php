<?php
// index.php
session_start();

// Mock: gruppi finti, in attesa del DB
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
    [
        'title' => 'App Gestione Esami',
        'course_name' => 'Ingegneria del Software',
        'description' => 'Web app per tracciare i progressi degli esami degli studenti.',
        'deadline' => '2026-07-10',
        'max_members' => 3,
        'current_members' => 1,
        'status' => 'open',
    ],
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>ProjectGroups - Gruppi per Elaborati</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">ProjectGroups</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Apri navigazione">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ms-auto">
    <li class="nav-item"><a class="nav-link" href="group_new.php">Crea gruppo</a></li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
            <span class="nav-link disabled">Ciao, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        </li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profilo</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="register.php">Registrati</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
    <?php endif; ?>
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
                        <p class="card-text flex-grow-1">
                            <?= htmlspecialchars($g['description']) ?>
                        </p>
                        <p class="card-text small text-muted mb-1">
                            Scadenza: <?= htmlspecialchars($g['deadline']) ?>
                        </p>
                        <p class="card-text small mb-2">
                            Membri: <?= (int)$g['current_members'] ?>/<?= (int)$g['max_members'] ?>
                            <?php if ($g['status'] === 'open'): ?>
                                <span class="badge bg-success ms-1">Aperto</span>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-1">Completo</span>
                            <?php endif; ?>
                        </p>
                        <a href="group_view.php?id=1" class="btn btn-sm btn-primary mt-auto">
                            Vedi dettagli
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>