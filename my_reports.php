<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
require_role('citizen');
date_default_timezone_set('Africa/Nairobi');
$query = $pdo->prepare('SELECT id, incident_type, county, location, incident_date, status, created_at
    FROM incidents WHERE reporter_id = ? ORDER BY created_at DESC');
$query->execute([(int) $_SESSION['user']['id']]);
$reports = $query->fetchAll();
$pageTitle = 'My Reports';
$currentPage = 'reports';
require __DIR__ . '/includes/citizen_header.php';
?>
<div class="page-heading"><div><span class="eyebrow">CITIZEN PORTAL</span><h1>My incident reports</h1><p>Review and track the status of your submitted wildlife conflict reports.</p></div><a href="<?= e(BASE_URL) ?>/report_incident.php" class="btn btn-primary">+ New report</a></div>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="success-note" role="status"><?= e((string) $_SESSION['flash_success']) ?></div><?php unset($_SESSION['flash_success']); ?><?php endif; ?>
<section class="surface-card reports-section"><div class="section-title"><div><span class="eyebrow">YOUR REPORT HISTORY</span><h2>Submitted incidents (<?= count($reports) ?>)</h2></div></div>
    <?php if (!$reports): ?><div class="empty-state"><div class="empty-icon" aria-hidden="true">▤</div><strong>No incident reports yet</strong><p>Start by reporting a wildlife incident you have observed.</p><a class="btn btn-primary" href="<?= e(BASE_URL) ?>/report_incident.php">Report an incident</a></div>
    <?php else: ?><div class="table-scroll"><table class="report-table"><thead><tr><th>Reference</th><th>Type</th><th>Location</th><th>Incident date</th><th>Status</th><th></th></tr></thead><tbody>
        <?php foreach ($reports as $report): ?><?php $statusStyle = match ($report['status']) {'Resolved'=>'resolved','In Progress'=>'progress',default=>'pending'}; ?>
        <tr><td>#KW-<?= (int) $report['id'] ?></td><td><span class="incident-type"><span class="incident-dot">!</span><?= e((string) $report['incident_type']) ?></span></td><td><?= e((string) $report['location']) ?><small class="subline"><?= e((string) $report['county']) ?></small></td><td><?= e(date('d M Y', strtotime((string) $report['incident_date']))) ?></td><td><span class="status-pill <?= e($statusStyle) ?>"><?= e((string) $report['status']) ?></span></td><td><a class="small-link" href="<?= e(BASE_URL) ?>/view_incident.php?id=<?= (int) $report['id'] ?>">Details →</a></td></tr>
        <?php endforeach; ?>
    </tbody></table></div><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/citizen_footer.php'; ?>
