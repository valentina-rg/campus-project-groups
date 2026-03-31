<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user_id(): ?int
{
    return is_logged_in() ? (int)$_SESSION['user_id'] : null;
}

function is_admin(): bool
{
    return isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
}

function require_admin(): void
{
    require_login();

    if (!is_admin()) {
        http_response_code(403);
        echo "Accesso negato: area riservata agli admin.";
        exit;
    }
}