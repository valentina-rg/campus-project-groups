<?php
// group_new.php
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title         = trim($_POST['title'] ?? '');
    $course_name   = trim($_POST['course_name'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $deadline      = $_POST['deadline'] ?? '';
    $max_members   = (int)($_POST['max_members'] ?? 4);
    $required_skills = trim($_POST['required_skills'] ?? '');

    if ($title === '') {
        $errors[] = 'Il titolo è obbligatorio.';
    }
    if ($course_name === '') {
        $errors[] = 'Il nome del corso è obbligatorio.';
    }
    if ($description === '') {
        $errors[] = 'La descrizione è obbligatoria.';
    }
    if ($max_members < 2 || $max_members > 10) {
        $errors[] = 'Il numero di membri deve essere tra 2 e 10.';
    }

    // Più avanti: validazione data (deadline >= oggi), ecc.

    if (!$errors) {
        // TODO: stasera, con il DB:
        // - require 'includes/db.php';
        // - inserire nella tabella projects_groups
        // - usare l'ID dell'utente loggato come owner_id

        $success = 'Gruppo creato (simulato, salvataggio DB da aggiungere).';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea gruppo progetto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">ProjectGroups</a>
  </div>
</nav>

<main class="container my-4" style="max-width: 700px;">
    <h1 class="mb-3 h3">Crea un nuovo gruppo per elaborato/progetto</h1>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="mb-3">
            <label for="title" class="form-label">Titolo del gruppo/progetto</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?= htmlspecialchars($title ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="course_name" class="form-label">Corso / Insegnamento</label>
            <input type="text" class="form-control" id="course_name" name="course_name"
                   placeholder="Es. Tecnologie Web, Reti, Basi di Dati"
                   value="<?= htmlspecialchars($course_name ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <textarea class="form-control" id="description" name="description" rows="4"
                      required><?= htmlspecialchars($description ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Scadenza (consegna progetto)</label>
            <input type="date" class="form-control" id="deadline" name="deadline"
                   value="<?= htmlspecialchars($deadline ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="max_members" class="form-label">Numero massimo di membri</label>
            <input type="number" class="form-control" id="max_members" name="max_members"
                   min="2" max="10"
                   value="<?= htmlspecialchars($max_members ?? 4) ?>" required>
        </div>

        <div class="mb-3">
            <label for="required_skills" class="form-label">Competenze richieste</label>
            <textarea class="form-control" id="required_skills" name="required_skills" rows="3"
                      placeholder="Es. PHP base, JavaScript, Bootstrap, scrittura relazione"><?= htmlspecialchars($required_skills ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crea gruppo</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>