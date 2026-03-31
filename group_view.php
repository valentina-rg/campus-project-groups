<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$group = null;
$members = [];

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo "ID gruppo non valido.";
    exit;
}

$groupId = (int)$_GET['id'];

$sql = "SELECT
            pg.id,
            pg.title,
            pg.course_name,
            pg.description,
            pg.deadline,
            pg.max_members,
            pg.status,
            pg.required_skills,
            pg.created_at,
            u.name AS owner_name,
            u.email AS owner_email
        FROM projects_groups pg
        JOIN users u ON pg.owner_id = u.id
        WHERE pg.id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $groupId]);
$group = $stmt->fetch();

if (!$group) {
    http_response_code(404);
    echo "Gruppo non trovato.";
    exit;
}

// per ora niente membri reali, ma la query è pronta per dopo
/*
$membersSql = "SELECT gm.role_in_group, u.name, u.email
               FROM group_members gm
               JOIN users u ON gm.user_id = u.id
               WHERE gm.group_id = :id";
$membersStmt = $pdo->prepare($membersSql);
$membersStmt->execute(['id' => $groupId]);
$members = $membersStmt->fetchAll();
*/
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($group['title']) ?> - ProjectGroups</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">ProjectGroups</a>
  </div>
</nav>

<main class="container my-4" style="max-width: 800px;">
    <a href="index.php" class="btn btn-link btn-sm mb-3">&larr; Torna alla lista gruppi</a>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-1"><?= htmlspecialchars($group['title']) ?></h1>
            <p class="text-muted mb-2">
                Corso: <?= htmlspecialchars($group['course_name']) ?>
            </p>

            <p class="small text-muted mb-2">
                Creato da: <?= htmlspecialchars($group['owner_name']) ?>
                (<?= htmlspecialchars($group['owner_email']) ?>)
            </p>

            <p class="small text-muted mb-2">
                Scadenza:
                <?= $group['deadline'] ? htmlspecialchars($group['deadline']) : 'Non specificata' ?>
            </p>

            <p class="small mb-3">
                Max membri: <?= (int)$group['max_members'] ?>
                <?php if ($group['status'] === 'open'): ?>
                    <span class="badge bg-success ms-1">Aperto</span>
                <?php elseif ($group['status'] === 'full'): ?>
                    <span class="badge bg-warning text-dark ms-1">Completo</span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-1">Chiuso</span>
                <?php endif; ?>
            </p>

            <h2 class="h6">Descrizione</h2>
            <p><?= nl2br(htmlspecialchars($group['description'])) ?></p>

            <?php if (!empty($group['required_skills'])): ?>
                <h2 class="h6 mt-4">Competenze richieste</h2>
                <p><?= nl2br(htmlspecialchars($group['required_skills'])) ?></p>
            <?php endif; ?>

            <?php
            // dopo il blocco "Competenze richieste"
            ?>

            <?php if (is_logged_in()): ?>
                <?php
                // controlla membership per questo utente
                $userId = current_user_id();
                $memStmt = $pdo->prepare(
                    "SELECT id FROM group_members WHERE group_id = :gid AND user_id = :uid LIMIT 1"
                );
                $memStmt->execute(['gid' => $groupId, 'uid' => $userId]);
                $alreadyMember = (bool)$memStmt->fetch();
                ?>
                <div class="mt-4">
                    <a href="group_join.php?id=<?= (int)$groupId ?>"
                       class="btn btn-<?= $alreadyMember ? 'outline-danger' : 'primary' ?>">
                        <?= $alreadyMember ? 'Esci dal gruppo' : 'Partecipa al gruppo' ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="mt-4">
                    <a href="login.php" class="btn btn-outline-primary">
                        Fai login per partecipare
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>