<?php
require_once __DIR__ . '/../config/auth.php';
require_role('citizen');
$pageTitle = 'Citizen Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="dashboard-heading">
    <span class="eyebrow">CITIZEN PORTAL</span>
    <h1>Citizen Dashboard</h1>
    <p>Welcome, <?= e((string) $_SESSION['user']['full_name']) ?>. This is your personal reporting area.</p>
</section>
<section class="feature-grid">
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Report an incident</h2><p>We will build a form for wildlife type, location, date, description and optional image.</p></article>
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Track my reports</h2><p>We will show pending, in-progress and resolved reports.</p></article>
    <article class="feature-card"><span class="role-tag">MY ACCOUNT</span><h2>Citizen access</h2><p>You are logged in as a citizen. Other dashboards are restricted.</p></article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
