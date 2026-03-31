<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

require_login();

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo "ID gruppo non valido.";
    exit;
}

$groupId = (int)$_GET['id'];
$userId  = current_user_id();

// controlla se esiste il gruppo e se è aperto
$stmt = $pdo->prepare("SELECT id, max_members, status FROM projects_groups WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $groupId]);
$group = $stmt->fetch();

if (!$group) {
    http_response_code(404);
    echo "Gruppo non trovato.";
    exit;
}

// controlla se l'utente è già membro
$checkStmt = $pdo->prepare("SELECT id FROM group_members WHERE group_id = :gid AND user_id = :uid LIMIT 1");
$checkStmt->execute(['gid' => $groupId, 'uid' => $userId]);
$isMember = (bool)$checkStmt->fetch();

// conta membri attuali
$countStmt = $pdo->prepare("SELECT COUNT(*) AS c FROM group_members WHERE group_id = :gid");
$countStmt->execute(['gid' => $groupId]);
$count = (int)$countStmt->fetchColumn();

if (!$isMember) {
    // JOIN: se non membro ed è aperto e non pieno
    if ($group['status'] !== 'open') {
        header('Location: group_view.php?id=' . $groupId);
        exit;
    }

    if ($count >= (int)$group['max_members']) {
        header('Location: group_view.php?id=' . $groupId);
        exit;
    }

    $insert = $pdo->prepare(
        "INSERT INTO group_members (group_id, user_id, role_in_group)
         VALUES (:gid, :uid, :role)"
    );
    $insert->execute([
        'gid' => $groupId,
        'uid' => $userId,
        'role' => null,
    ]);

    $count++;

    if ($count >= (int)$group['max_members']) {
        $updateStatus = $pdo->prepare("UPDATE projects_groups SET status = 'full' WHERE id = :id");
        $updateStatus->execute(['id' => $groupId]);
    }
} else {
    // LEAVE: se è già membro, lo rimuoviamo
    $delete = $pdo->prepare("DELETE FROM group_members WHERE group_id = :gid AND user_id = :uid");
    $delete->execute(['gid' => $groupId, 'uid' => $userId]);

    if ($group['status'] === 'full') {
        $updateStatus = $pdo->prepare("UPDATE projects_groups SET status = 'open' WHERE id = :id");
        $updateStatus->execute(['id' => $groupId]);
    }
}

header('Location: group_view.php?id=' . $groupId);
exit;