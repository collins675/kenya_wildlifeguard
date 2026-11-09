<?php
declare(strict_types=1);

// Local Laragon settings. Change these if you changed MySQL credentials or port.
$databaseHost = '127.0.0.1';
$databasePort = '3306';
$databaseName = 'kenya_wildlifeguard';
$databaseUser = 'root';
$databasePassword = '';

try {
    $pdo = new PDO(
        "mysql:host={$databaseHost};port={$databasePort};dbname={$databaseName};charset=utf8mb4",
        $databaseUser,
        $databasePassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log('WildlifeGuard database error: ' . $exception->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check your Laragon MySQL settings.');
}
