<?php
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    redirect_to(dashboard_for((string) $_SESSION['user']['role']));
}

$pageTitle = 'Citizen registration';
$errors = [];
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if (!csrf_valid((string) ($_POST['csrf_token'] ?? ''))) {
        $errors[] = 'Your session expired. Refresh the page and try again.';
    }
    if (strlen($fullName) < 2 || strlen($fullName) > 100) {
        $errors[] = 'Enter your full name (2 to 100 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Use a password with at least 8 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Your passwords do not match.';
    }

    if (!$errors) {
        try {
            $query = $pdo->prepare(
                'INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)'
            );
            // Public sign-up ALWAYS creates a citizen, regardless of form submissions.
            $query->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), 'citizen']);
            $_SESSION['flash_success'] = 'Account created successfully. You can now log in.';
            redirect_to('/login.php');
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $errors[] = 'This email address is already registered.';
            } else {
                error_log('WildlifeGuard registration error: ' . $exception->getMessage());
                $errors[] = 'Could not create your account. Please try again.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-shell">
    <div class="auth-intro">
        <span class="eyebrow">JOIN KENYA WILDLIFEGUARD</span>
        <h1>Create a citizen account</h1>
        <p>Register to use the wildlife incident reporting platform. Ranger and officer accounts will be created separately by an authorized administrator.</p>
    </div>
    <div class="form-card">
        <h2>Citizen registration</h2>
        <?php if ($errors): ?>
            <div class="alert error" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form action="<?= e(BASE_URL) ?>/register.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <label for="full_name">Full name</label>
            <input id="full_name" name="full_name" type="text" maxlength="100" value="<?= e($fullName) ?>" autocomplete="name" required>
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" maxlength="150" value="<?= e($email) ?>" autocomplete="email" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
            <small class="field-note">Use at least 8 characters.</small>
            <label for="confirm_password">Confirm password</label>
            <input id="confirm_password" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required>
            <button class="btn full" type="submit">Create account</button>
        </form>
        <p class="form-bottom">Already have an account? <a href="<?= e(BASE_URL) ?>/login.php">Log in</a></p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
