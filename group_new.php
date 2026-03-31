<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$errors = [];
$title = '';
$course_name = '';
$description = '';
$deadline = '';
$max_members = 4;
$required_skills = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $course_name = trim($_POST['course_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline = $_POST['deadline'] ?? '';
    $max_members = (int)($_POST['max_members'] ?? 4);
    $required_skills = trim($_POST['required_skills'] ?? '');

    if ($title === '') {
        $errors[] = 'Il titolo è obbligatorio.';
    }

    if ($course_name === '') {
        $errors[] = 'Il corso è obbligatorio.';
    }

    if ($description === '') {
        $errors[] = 'La descrizione è obbligatoria.';
    }

    if ($max_members < 2 || $max_members > 10) {
        $errors[] = 'Il numero massimo di membri deve essere tra 2 e 10.';
    }

    if (!$errors) {
        $sql = "INSERT INTO projects_groups
                (title, course_name, description, deadline, max_members, owner_id, required_skills, status)
                VALUES
                (:title, :course_name, :description, :deadline, :max_members, :owner_id, :required_skills, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'course_name' => $course_name,
            'description' => $description,
            'deadline' => $deadline !== '' ? $deadline : null,
            'max_members' => $max_members,
            'owner_id' => $_SESSION['user_id'],
            'required_skills' => $required_skills,
            'status' => 'open',
        ]);

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea gruppo - ProjectGroups</title>
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

<main class="container my-4" style="max-width: 720px;">
    <h1 class="mb-4 h3">Crea un nuovo gruppo</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="group_new.php" novalidate>
        <div class="mb-3">
            <label for="title" class="form-label">Titolo del progetto</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?= htmlspecialchars($title) ?>" required>
        </div>

        <div class="mb-3">
            <label for="course_name" class="form-label">Corso / Insegnamento</label>
            <input type="text" class="form-control" id="course_name" name="course_name"
                   value="<?= htmlspecialchars($course_name) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($description) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Scadenza</label>
            <input type="date" class="form-control" id="deadline" name="deadline"
                   value="<?= htmlspecialchars($deadline) ?>">
        </div>

        <div class="mb-3">
            <label for="max_members" class="form-label">Numero massimo di membri</label>
            <input type="number" class="form-control" id="max_members" name="max_members"
                   min="2" max="10" value="<?= htmlspecialchars((string)$max_members) ?>" required>
        </div>

        <div class="mb-3">
            <label for="required_skills" class="form-label">Competenze richieste</label>
            <textarea class="form-control" id="required_skills" name="required_skills" rows="3"><?= htmlspecialchars($required_skills) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crea gruppo</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>