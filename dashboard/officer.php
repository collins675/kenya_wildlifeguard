<?php
require_once __DIR__ . '/../config/auth.php';
require_role('officer');
$pageTitle = 'KWS Officer Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="dashboard-heading">
    <span class="eyebrow">KWS OFFICER PORTAL</span>
    <h1>KWS Officer Dashboard</h1>
    <p>Welcome, <?= e((string) $_SESSION['user']['full_name']) ?>. This area will manage reports and ranger assignments.</p>
</section>
<section class="feature-grid">
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Review reports</h2><p>We will add a list of reported human–wildlife conflict incidents.</p></article>
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Assign rangers</h2><p>We will allow authorized officers to assign a report to a ranger.</p></article>
    <article class="feature-card"><span class="role-tag">NEXT FEATURE</span><h2>Summary statistics</h2><p>We will add basic charts and location-based summaries after incident reporting is built.</p></article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
