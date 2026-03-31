<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$sql = "SELECT
            pg.id,
            pg.title,
            pg.course_name,
            pg.description,
            pg.deadline,
            pg.max_members,
            pg.status,
            pg.created_at,
            u.name AS owner_name
        FROM projects_groups pg
        JOIN users u ON pg.owner_id = u.id
        ORDER BY pg.created_at DESC";

$stmt = $pdo->query($sql);
$groups = $stmt->fetchAll();
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

    <?php if (empty($groups)): ?>
        <div class="alert alert-info">
            Nessun gruppo disponibile al momento. Crea il primo gruppo!
        </div>
    <?php else: ?>
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
                                Creato da: <?= htmlspecialchars($g['owner_name']) ?>
                            </p>

                            <p class="card-text small text-muted mb-1">
                                Scadenza:
                                <?= $g['deadline'] ? htmlspecialchars($g['deadline']) : 'Non specificata' ?>
                            </p>

                            <p class="card-text small mb-2">
                                Max membri: <?= (int)$g['max_members'] ?>
                                <?php if ($g['status'] === 'open'): ?>
                                    <span class="badge bg-success ms-1">Aperto</span>
                                <?php elseif ($g['status'] === 'full'): ?>
                                    <span class="badge bg-warning text-dark ms-1">Completo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary ms-1">Chiuso</span>
                                <?php endif; ?>
                            </p>

                            <a href="group_view.php?id=<?= (int)$g['id'] ?>" class="btn btn-sm btn-primary mt-auto">
                                Vedi dettagli
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>