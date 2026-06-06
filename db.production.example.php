<?php
$dbHost = 'localhost';
$dbName = 'WEKA_DATABASE_NAME_HAPA';
$dbUser = 'WEKA_DATABASE_USER_HAPA';
$dbPass = 'WEKA_DATABASE_PASSWORD_HAPA';

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $error) {
    die('Database connection failed.');
}
