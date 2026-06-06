<?php
session_start();
$validUsers = [
    'admin' => [ 
        'password' => '123456',
        'role' => 'admin'],

    'mteja' =>[
        'password' => '123456',
        'role' => 'user']
    
];

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentUserRole(): string
{
    return $_SESSION['role'] ?? 'customer';
}

function isAdmin(): bool
{
    return isLoggedIn() && currentUserRole() === 'admin';
}

function requireAdmin(): void
{
    requireLogin();

    if (!isAdmin()) {
        header('Location: products.php');
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
