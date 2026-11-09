<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../includes/report_helpers.php';
require_role('officer');
date_default_timezone_set('Africa/Nairobi');
$summary=$pdo->query("SELECT COUNT(*) total,COALESCE(SUM(status='Pending'),0) pending,COALESCE(SUM(status='In Progress'),0) in_progress,COALESCE(SUM(status='Resolved'),0) resolved FROM incidents")->fetch();
$cases=$pdo->query('SELECT i.id,i.incident_type,i.county,i.location,i.status,i.created_at,i.assigned_ranger_id,u.full_name AS reporter_name,r.full_name AS ranger_name FROM incidents i JOIN users u ON u.id=i.reporter_id LEFT JOIN users r ON r.id=i.assigned_ranger_id ORDER BY i.created_at DESC LIMIT 100')->fetchAll();
$rangers=$pdo->query("SELECT id,full_name,email FROM users WHERE role='ranger' ORDER BY full_name")->fetchAll();
$pageTitle='Officer Dashboard';$currentPage='dashboard';$portalRole='officer';
require __DIR__ . '/../includes/portal_header.php';
?>
<section class="hero-dashboard hero-officer"><div class="hero-copy"><span class="hero-kicker">OFFICER PORTAL · CASE MANAGEMENT</span><h1>KWS Officer Dashboard</h1><p>Review submitted reports, assign available ranger accounts and monitor case progress from one place.</p><a href="#cases" class="hero-action">Manage incident reports →</a></div></section>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="success-note" role="status"><?= e((string)$_SESSION['flash_success']) ?></div><?php unset($_SESSION['flash_success']); ?><?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="error-note" role="alert"><?= e((string)$_SESSION['flash_error']) ?></div><?php unset($_SESSION['flash_error']); ?><?php endif; ?>
<section class="stats-grid" aria-label="All system report totals">
 <article class="stat-card red"><span class="stat-symbol">▤</span><div><span class="stat-label">Total Incidents</span><strong class="stat-value"><?= (int)$summary['total'] ?></strong><small>Recorded reports</small></div></article>
 <article class="stat-card amber"><span class="stat-symbol">◷</span><div><span class="stat-label">Pending</span><strong class="stat-value"><?= (int)$summary['pending'] ?></strong><small>Awaiting assignment</small></div></article>
 <article class="stat-card blue"><span class="stat-symbol">↻</span><div><span class="stat-label">In Progress</span><strong class="stat-value"><?= (int)$summary['in_progress'] ?></strong><small>Active incidents</small></div></article>
 <article class="stat-card green"><span class="stat-symbol">✓</span><div><span class="stat-label">Resolved</span><strong class="stat-value"><?= (int)$summary['resolved'] ?></strong><small>Completed reports</small></div></article>
</section>
<div class="tool-grid">
 <section class="surface-card" id="cases"><div class="section-title"><h2>Incident Management</h2><span class="heading-muted">Latest <?= count($cases) ?> reports</span></div>
 <?php if (!$cases): ?><div class="empty-state"><span>▤</span><h3>No reported incidents yet</h3><p>Citizen reports will show here when they are submitted.</p></div>
 <?php else: ?><div class="table-scroll"><table class="report-table"><thead><tr><th>Incident</th><th>Location</th><th>Status</th><th>Assign Ranger</th></tr></thead><tbody>
 <?php foreach ($cases as $case): ?><tr>
 <td><a class="small-link" href="<?= e(BASE_URL) ?>/view_incident.php?id=<?= (int)$case['id'] ?>">#KW-<?= (int)$case['id'] ?> · <?= e((string)$case['incident_type']) ?></a><small class="subline">Reported by <?= e((string)$case['reporter_name']) ?></small></td>
 <td><?= e((string)$case['county']) ?><small class="subline"><?= e((string)$case['location']) ?></small></td>
 <td><span class="status-pill <?= e(status_class((string)$case['status'])) ?>"><?= e((string)$case['status']) ?></span></td>
 <td><?php if ($case['status'] !== 'Resolved'): ?>
 <form class="assignment-form" action="<?= e(BASE_URL) ?>/assign_ranger.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="incident_id" value="<?= (int)$case['id'] ?>"><label class="sr-only" for="assign-<?= (int)$case['id'] ?>">Choose ranger</label><select id="assign-<?= (int)$case['id'] ?>" name="ranger_id" required><option value="">Choose ranger</option><?php foreach ($rangers as $ranger): ?><option value="<?= (int)$ranger['id'] ?>" <?= (int)$case['assigned_ranger_id'] === (int)$ranger['id'] ? 'selected' : '' ?>><?= e((string)$ranger['full_name']) ?></option><?php endforeach; ?></select><button type="submit" <?= !$rangers ? 'disabled' : '' ?>>Assign</button></form>
 <?php else: ?><span class="tiny-notice"><?= $case['ranger_name'] ? e((string)$case['ranger_name']) : 'Completed' ?></span><?php endif; ?></td>
 </tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
 <aside><div class="info-photo-card"><img src="<?= e(BASE_URL) ?>/assets/wildlife/giraffes.webp" alt="Two giraffes in a Kenyan savannah landscape" loading="lazy"><div><h2>Information helps coordination</h2><p>Review incidents carefully before making an assignment.</p></div></div><section class="surface-card" id="rangers" style="margin-top:16px"><div class="section-title"><h2>Ranger Accounts</h2><span class="heading-muted"><?= count($rangers) ?> available</span></div>
 <?php if (!$rangers): ?><p class="empty-panel">No ranger accounts have been created. An authorized administrator must create ranger users before assigning cases.</p><?php else: ?>
 <?php foreach ($rangers as $ranger): ?><div class="recent-item"><span class="avatar header-avatar"><?= e(strtoupper(substr((string)$ranger['full_name'],0,1))) ?></span><span class="recent-copy"><strong><?= e((string)$ranger['full_name']) ?></strong><small><?= e((string)$ranger['email']) ?></small></span></div><?php endforeach; ?><?php endif; ?>
 </section></aside>
</div>
<?php require __DIR__ . '/../includes/portal_footer.php'; ?>
