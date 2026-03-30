<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$errors = [];
$success = '';

$name = '';
$email = '';
$degree_course = '';
$skills = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $degree_course = trim($_POST['degree_course'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($name === '') {
        $errors[] = 'Il nome è obbligatorio.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Inserisci un indirizzo email valido.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'La password deve avere almeno 8 caratteri.';
    }

    if ($password !== $password_confirm) {
        $errors[] = 'Le password non coincidono.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $errors[] = 'Esiste già un account con questa email.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password_hash, role, degree_course, skills)
                    VALUES (:name, :email, :password_hash, :role, :degree_course, :skills)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => 'student',
                'degree_course' => $degree_course,
                'skills' => $skills,
            ]);

            $success = 'Registrazione completata con successo.';
            $name = '';
            $email = '';
            $degree_course = '';
            $skills = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - ProjectGroups</title>
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

<main class="container my-4" style="max-width: 700px;">
    <h1 class="mb-4 h3">Registrazione</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label for="degree_course" class="form-label">Corso di laurea</label>
            <input
                type="text"
                class="form-control"
                id="degree_course"
                name="degree_course"
                value="<?= htmlspecialchars($degree_course) ?>"
            >
        </div>

        <div class="mb-3">
            <label for="skills" class="form-label">Competenze</label>
            <textarea
                class="form-control"
                id="skills"
                name="skills"
                rows="3"
                placeholder="Es. PHP base, JavaScript, Bootstrap, documentazione"><?= htmlspecialchars($skills) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                required
            >
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Conferma password</label>
            <input
                type="password"
                class="form-control"
                id="password_confirm"
                name="password_confirm"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrati</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>