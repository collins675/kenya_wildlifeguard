<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../includes/report_helpers.php';
require_role('ranger');
date_default_timezone_set('Africa/Nairobi');
$userId = (int)$_SESSION['user']['id'];
$summaryStmt = $pdo->prepare("SELECT COUNT(*) total, COALESCE(SUM(status='Pending'),0) pending, COALESCE(SUM(status='In Progress'),0) in_progress, COALESCE(SUM(status='Resolved'),0) resolved FROM incidents WHERE assigned_ranger_id=?");
$summaryStmt->execute([$userId]); $summary = $summaryStmt->fetch();
$incStmt = $pdo->prepare('SELECT i.*,u.full_name reporter_name FROM incidents i JOIN users u ON u.id=i.reporter_id WHERE i.assigned_ranger_id=? ORDER BY (i.status = \'Resolved\') ASC, i.created_at DESC LIMIT 100');
$incStmt->execute([$userId]); $incidents=$incStmt->fetchAll();
$pageTitle='Ranger Dashboard';$currentPage='dashboard';$portalRole='ranger';
require __DIR__ . '/../includes/portal_header.php';
?>
<section class="hero-dashboard hero-ranger"><div class="hero-copy"><span class="hero-kicker">RANGER PORTAL · INCIDENT RESPONSE</span><h1>Ranger Dashboard</h1><p>Review assigned incidents and record progress as you work through your assigned cases.</p><a href="#assignments" class="hero-action">View assignments →</a></div></section>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="success-note" role="status"><?= e((string)$_SESSION['flash_success']) ?></div><?php unset($_SESSION['flash_success']); ?><?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?><div class="error-note" role="alert"><?= e((string)$_SESSION['flash_error']) ?></div><?php unset($_SESSION['flash_error']); ?><?php endif; ?>
<section class="stats-grid" aria-label="Assigned report totals">
 <article class="stat-card red"><span class="stat-symbol">▤</span><div><span class="stat-label">Assigned</span><strong class="stat-value"><?= (int)$summary['total'] ?></strong><small>Total cases</small></div></article>
 <article class="stat-card amber"><span class="stat-symbol">◷</span><div><span class="stat-label">Pending</span><strong class="stat-value"><?= (int)$summary['pending'] ?></strong><small>Awaiting action</small></div></article>
 <article class="stat-card blue"><span class="stat-symbol">↻</span><div><span class="stat-label">In Progress</span><strong class="stat-value"><?= (int)$summary['in_progress'] ?></strong><small>Currently assigned</small></div></article>
 <article class="stat-card green"><span class="stat-symbol">✓</span><div><span class="stat-label">Resolved</span><strong class="stat-value"><?= (int)$summary['resolved'] ?></strong><small>Completed cases</small></div></article>
</section>
<div class="tool-grid">
 <section class="surface-card" id="assignments"><div class="section-title"><h2>My Assigned Incidents</h2><span class="heading-muted"><?= count($incidents) ?> shown</span></div>
 <?php if (!$incidents): ?><div class="empty-state"><span>▤</span><h3>No assignments yet</h3><p>An officer must assign an incident to your ranger account before it appears here.</p></div>
 <?php else: ?><div class="table-scroll"><table class="report-table"><thead><tr><th>Report</th><th>Location</th><th>Status</th><th>Update</th></tr></thead><tbody>
 <?php foreach ($incidents as $incident): ?><tr>
 <td><a class="small-link" href="<?= e(BASE_URL) ?>/view_incident.php?id=<?= (int)$incident['id'] ?>">#KW-<?= (int)$incident['id'] ?></a><small class="subline"><?= e((string)$incident['incident_type']) ?></small></td>
 <td><?= e((string)$incident['county']) ?><small class="subline"><?= e((string)$incident['location']) ?></small></td>
 <td><span class="status-pill <?= e(status_class((string)$incident['status'])) ?>"><?= e((string)$incident['status']) ?></span></td>
 <td><?php if ($incident['status'] !== 'Resolved'): ?>
 <form class="status-form" action="<?= e(BASE_URL) ?>/update_incident_status.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="incident_id" value="<?= (int)$incident['id'] ?>"><label class="sr-only" for="status-<?= (int)$incident['id'] ?>">New status</label><select name="status" id="status-<?= (int)$incident['id'] ?>"><option value="In Progress">In Progress</option><option value="Resolved">Resolved</option></select><button type="submit">Update</button></form>
 <?php else: ?><span class="tiny-notice">Completed</span><?php endif; ?></td></tr><?php endforeach; ?>
 </tbody></table></div><?php endif; ?>
 </section>
 <aside><div class="info-photo-card"><img src="<?= e(BASE_URL) ?>/assets/wildlife/zebra-herd.webp" alt="Zebra herd in a natural landscape" loading="lazy"><div><h2>Protecting communities</h2><p>Document response progress and respect wildlife at a safe distance.</p></div></div><section class="surface-card" id="status-guide" style="margin-top:16px"><h2>Status Guide</h2><ol class="help-list"><li><strong>Pending:</strong> reported and waiting for action.</li><li><strong>In Progress:</strong> a ranger is working on the case.</li><li><strong>Resolved:</strong> the case has been completed.</li></ol><p class="tiny-notice">Only your assigned incidents can be updated.</p></section></aside>
</div>
<?php require __DIR__ . '/../includes/portal_footer.php'; ?>
