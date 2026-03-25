<?php
// register.php
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Leggi input
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // 2. Validazione base
    if ($name === '') {
        $errors[] = 'Il nome è obbligatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email non valida.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'La password deve avere almeno 6 caratteri.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Le password non coincidono.';
    }

    // 3. Se nessun errore, qui più tardi metteremo l'inserimento nel DB
    if (!$errors) {
        // TODO: stasera, con il DB attivo:
        // - require_once 'includes/db.php';
        // - controllare se esiste già l'email
        // - inserire l'utente con password_hash

        // Per ora simuliamo successo
        $success = 'Registrazione OK (simulata, DB da collegare).';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Gruppi Elaborati</title>
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

<main class="container my-4" style="max-width: 500px;">
    <h1 class="mb-3 h3">Registrazione</h1>

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
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?= htmlspecialchars($name ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email universitaria</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?= htmlspecialchars($email ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Conferma password</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Crea account</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>