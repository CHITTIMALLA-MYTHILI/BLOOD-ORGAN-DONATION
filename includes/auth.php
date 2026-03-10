<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(array $roles = []): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /public/login.php');
        exit;
    }

    if (!empty($roles) && !in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(403);
        echo 'Access denied';
        exit;
    }
}
?>
