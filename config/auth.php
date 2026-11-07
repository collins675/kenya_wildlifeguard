<?php
declare(strict_types=1);

// Project is reached at http://localhost/kenya-wildlifeguard/.
const BASE_URL = '/kenya-wildlifeguard';

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    $httpsEnabled = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $httpsEnabled,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/db.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_valid(string $submitted): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submitted);
}

function redirect_to(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function dashboard_for(string $role): string
{
    switch ($role) {
        case 'citizen': return '/dashboard/citizen.php';
        case 'ranger': return '/dashboard/ranger.php';
        case 'officer': return '/dashboard/officer.php';
        default: return '/login.php';
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function require_role(string $requiredRole): void
{
    if (!is_logged_in()) {
        redirect_to('/login.php');
    }
    if (($_SESSION['user']['role'] ?? '') !== $requiredRole) {
        redirect_to(dashboard_for((string) ($_SESSION['user']['role'] ?? '')));
    }
}
