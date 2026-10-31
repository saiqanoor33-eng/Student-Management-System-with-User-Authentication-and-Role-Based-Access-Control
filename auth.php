<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireRole(array $roles): void
{
    requireLogin();

    if (!in_array($_SESSION['role'], $roles, true)) {
        http_response_code(403);
        exit('403 Forbidden: You are not authorized to access this page.');
    }
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')
    ) {
        http_response_code(403);
        exit('Invalid security token. Please refresh the page.');
    }
}
