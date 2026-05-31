<?php
session_start();

$validUsers = [
    'Gabrielhezron' => '123456',
    'mteja' => '123456',
];

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function displayName(string $name): string
{
    $cleanName = trim($name);

    if (strpos($cleanName, '@') !== false) {
        $cleanName = explode('@', $cleanName)[0];
    }

    return htmlspecialchars($cleanName ?: 'Mteja', ENT_QUOTES, 'UTF-8');
}
