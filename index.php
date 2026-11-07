<?php
require_once __DIR__ . '/config/auth.php';
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="eyebrow">WILDLIFE INCIDENT REPORTING & MANAGEMENT</div>
    <h1>Connecting communities and wildlife response teams.</h1>
    <p class="lead">A web-based academic prototype for recording human–wildlife conflict reports, assigning rangers and following up on incidents in Kenya.</p>
    <div class="actions">
        <?php if (is_logged_in()): ?>
            <a class="btn" href="<?= e(BASE_URL . dashboard_for((string) $_SESSION['user']['role'])) ?>">Go to my dashboard</a>
        <?php else: ?>
            <a class="btn" href="<?= e(BASE_URL) ?>/register.php">Register as a citizen</a>
            <a class="btn btn-outline" href="<?= e(BASE_URL) ?>/login.php">Log in</a>
        <?php endif; ?>
    </div>
</section>
<section class="feature-grid" aria-label="User roles">
    <article class="feature-card"><span class="role-tag">CITIZEN</span><h2>Report</h2><p>Submit wildlife incidents and follow their progress.</p></article>
    <article class="feature-card"><span class="role-tag">RANGER</span><h2>Respond</h2><p>View assigned incidents and update case status.</p></article>
    <article class="feature-card"><span class="role-tag">KWS OFFICER</span><h2>Coordinate</h2><p>Assign cases and monitor incident information.</p></article>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
