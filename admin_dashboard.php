<?php
session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_admin();

$sql = "SELECT
            pg.id,
            pg.title,
            pg.course_name,
            pg.status,
            pg.max_members,
            pg.created_at,
            u.name  AS owner_name,
            u.email AS owner_email
        FROM projects_groups pg
        JOIN users u ON pg.owner_id = u.id
        ORDER BY pg.created_at DESC";

$stmt   = $pdo->query($sql);
$groups = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello Admin - ProjectGroups</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">ProjectGroups</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Home</a>
            <a href="profile.php" class="btn btn-outline-light btn-sm me-2">Profilo</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Pannello Admin</h1>
            <p class="text-muted mb-0">Gestione dei gruppi del servizio (solo per amministratori).</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($groups)): ?>
                <p class="text-muted mb-0">Nessun gruppo presente.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titolo</th>
                                <th>Corso</th>
                                <th>Creatore</th>
                                <th>Status</th>
                                <th>Max membri</th>
                                <th>Creato il</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups as $group): ?>
                                <tr>
                                    <td><?= (int)$group['id'] ?></td>
                                    <td><?= htmlspecialchars($group['title']) ?></td>
                                    <td><?= htmlspecialchars($group['course_name']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($group['owner_name']) ?><br>
                                        <span class="text-muted small">
                                            <?= htmlspecialchars($group['owner_email']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($group['status'] === 'open'): ?>
                                            <span class="badge bg-success">Aperto</span>
                                        <?php elseif ($group['status'] === 'full'): ?>
                                            <span class="badge bg-warning text-dark">Completo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Chiuso</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int)$group['max_members'] ?></td>
                                    <td><?= htmlspecialchars($group['created_at']) ?></td>
                                    <td>
                                        <a href="group_view.php?id=<?= (int)$group['id'] ?>"
                                           class="btn btn-sm btn-outline-primary mb-1">
                                            Vedi
                                        </a>
                                        <a href="admin_edit_group.php?id=<?= (int)$group['id'] ?>"
                                           class="btn btn-sm btn-outline-warning mb-1">
                                            Modifica
                                        </a>
                                        <a href="admin_delete_group.php?id=<?= (int)$group['id'] ?>"
                                           class="btn btn-sm btn-outline-danger mb-1"
                                           onclick="return confirm('Sei sicura di voler eliminare questo gruppo?');">
                                            Elimina
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>