<?php
$pageTitle = $pageTitle ?? 'Kenya WildlifeGuard';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Kenya WildlifeGuard</title>
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= e(BASE_URL) ?>/index.php"><span class="brand-icon">KW</span> Kenya WildlifeGuard</a>
        <nav class="navigation" aria-label="Main navigation">
            <?php if (is_logged_in()): ?>
                <span class="welcome">Hello, <?= e((string) $_SESSION['user']['full_name']) ?></span>
                <a href="<?= e(BASE_URL . dashboard_for((string) $_SESSION['user']['role'])) ?>">Dashboard</a>
                <form action="<?= e(BASE_URL) ?>/logout.php" method="post" class="inline-form">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button type="submit" class="link-button">Log out</button>
                </form>
            <?php else: ?>
                <a href="<?= e(BASE_URL) ?>/login.php">Log in</a>
                <a class="nav-pill" href="<?= e(BASE_URL) ?>/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container main-area">
