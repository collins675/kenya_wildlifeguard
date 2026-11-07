<?php
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    redirect_to(dashboard_for((string) $_SESSION['user']['role']));
}

$pageTitle = 'Login';
$error = '';
$email = '';
$success = (string) ($_SESSION['flash_success'] ?? '');
unset($_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valid((string) ($_POST['csrf_token'] ?? ''))) {
        $error = 'Your session expired. Refresh the page and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter your email address and password.';
    } else {
        $query = $pdo->prepare('SELECT id, full_name, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $query->execute([$email]);
        $user = $query->fetch();

        if ($user && password_verify($password, (string) $user['password_hash']) &&
            in_array($user['role'], ['citizen', 'ranger', 'officer'], true)) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'full_name' => (string) $user['full_name'],
                'role' => (string) $user['role'],
            ];
            unset($_SESSION['csrf_token']);
            redirect_to(dashboard_for((string) $user['role']));
        }
        $error = 'Incorrect email address or password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-shell">
    <div class="auth-intro">
        <span class="eyebrow">WELCOME BACK</span>
        <h1>Sign in to Kenya WildlifeGuard</h1>
        <p>Citizens, rangers and KWS officers will use one login page. Each account will open the dashboard assigned to its role.</p>
    </div>
    <div class="form-card">
        <h2>Log in</h2>
        <?php if ($success): ?><div class="alert success" role="status"><?= e($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <form action="<?= e(BASE_URL) ?>/login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" maxlength="150" value="<?= e($email) ?>" autocomplete="email" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button class="btn full" type="submit">Log in</button>
        </form>
        <p class="form-bottom">New here? <a href="<?= e(BASE_URL) ?>/register.php">Create a citizen account</a></p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
