<?php
session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$userId = current_user_id();

// gruppi creati da me
$createdStmt = $pdo->prepare("
    SELECT id, title, course_name, deadline, status, max_members, created_at
    FROM projects_groups
    WHERE owner_id = :uid
    ORDER BY created_at DESC
");
$createdStmt->execute(['uid' => $userId]);
$createdGroups = $createdStmt->fetchAll();

// gruppi a cui partecipo
$joinedStmt = $pdo->prepare("
    SELECT
        pg.id,
        pg.title,
        pg.course_name,
        pg.deadline,
        pg.status,
        pg.max_members,
        gm.role_in_group
    FROM group_members gm
    JOIN projects_groups pg ON gm.group_id = pg.id
    WHERE gm.user_id = :uid
    ORDER BY pg.created_at DESC
");
$joinedStmt->execute(['uid' => $userId]);
$joinedGroups = $joinedStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Il mio profilo - ProjectGroups</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">ProjectGroups</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Home</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<main class="container my-4">
    <div class="mb-4">
        <h1 class="h3">Il mio profilo</h1>
        <p class="text-muted mb-0">Qui vedi i gruppi che hai creato e quelli a cui partecipi.</p>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Gruppi creati da me</h2>
                        <a href="group_create.php" class="btn btn-primary btn-sm">Nuovo gruppo</a>
                    </div>

                    <?php if (empty($createdGroups)): ?>
                        <p class="text-muted mb-0">Non hai ancora creato nessun gruppo.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($createdGroups as $group): ?>
                                <div class="list-group-item px-0">
                                    <h3 class="h6 mb-1"><?= htmlspecialchars($group['title']) ?></h3>
                                    <p class="mb-1 text-muted small">
                                        Corso: <?= htmlspecialchars($group['course_name']) ?>
                                    </p>
                                    <p class="mb-2 small">
                                        Scadenza: <?= $group['deadline'] ? htmlspecialchars($group['deadline']) : 'Non specificata' ?><br>
                                        Max membri: <?= (int)$group['max_members'] ?>
                                    </p>

                                    <?php if ($group['status'] === 'open'): ?>
                                        <span class="badge bg-success">Aperto</span>
                                    <?php elseif ($group['status'] === 'full'): ?>
                                        <span class="badge bg-warning text-dark">Completo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chiuso</span>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <a href="group_view.php?id=<?= (int)$group['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            Vedi dettagli
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Gruppi a cui partecipo</h2>

                    <?php if (empty($joinedGroups)): ?>
                        <p class="text-muted mb-0">Non partecipi ancora a nessun gruppo.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($joinedGroups as $group): ?>
                                <div class="list-group-item px-0">
                                    <h3 class="h6 mb-1"><?= htmlspecialchars($group['title']) ?></h3>
                                    <p class="mb-1 text-muted small">
                                        Corso: <?= htmlspecialchars($group['course_name']) ?>
                                    </p>
                                    <p class="mb-2 small">
                                        Scadenza: <?= $group['deadline'] ? htmlspecialchars($group['deadline']) : 'Non specificata' ?><br>
                                        Ruolo: <?= $group['role_in_group'] ? htmlspecialchars($group['role_in_group']) : 'Non specificato' ?>
                                    </p>

                                    <?php if ($group['status'] === 'open'): ?>
                                        <span class="badge bg-success">Aperto</span>
                                    <?php elseif ($group['status'] === 'full'): ?>
                                        <span class="badge bg-warning text-dark">Completo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chiuso</span>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <a href="group_view.php?id=<?= (int)$group['id'] ?>" class="btn btn-outline-primary btn-sm">
                                            Vedi dettagli
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>