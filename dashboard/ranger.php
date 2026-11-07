<?php
require_once __DIR__ . '/../config/auth.php';
require_role('ranger');
$pageTitle = 'Ranger Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="dashboard-heading">
    <span class="eyebrow">RANGER PORTAL</span>
    <h1>Ranger Dashboard</h1>
    <p>Welcome, <?= e((string) $_SESSION['user']['full_name']) ?>. Assigned incidents and progress updates will appear here.</p>
</section>
<section class="feature-grid">
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Assigned incidents</h2><p>We will list the cases assigned to your ranger account.</p></article>
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Update status</h2><p>We will add progress notes and incident status updates.</p></article>
    <article class="feature-card"><span class="role-tag">ACCESS CONTROL</span><h2>Ranger-only access</h2><p>This page is protected and only available to ranger accounts.</p></article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
