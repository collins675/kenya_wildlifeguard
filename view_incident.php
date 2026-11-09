<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/includes/report_helpers.php';
if (!is_logged_in()) redirect_to('/login.php');
$role=(string)($_SESSION['user']['role'] ?? '');
if (!in_array($role,['citizen','ranger','officer'],true)) { http_response_code(403); exit('Access denied.'); }
date_default_timezone_set('Africa/Nairobi');
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); exit('Incident not found.'); }
$sql='SELECT i.*,u.full_name reporter_name,r.full_name ranger_name FROM incidents i JOIN users u ON u.id=i.reporter_id LEFT JOIN users r ON r.id=i.assigned_ranger_id WHERE i.id=?';
$params=[$id];
if ($role==='citizen') {$sql.=' AND i.reporter_id=?';$params[]=(int)$_SESSION['user']['id'];}
if ($role==='ranger') {$sql.=' AND i.assigned_ranger_id=?';$params[]=(int)$_SESSION['user']['id'];}
$sql.=' LIMIT 1';
$query=$pdo->prepare($sql);$query->execute($params);$report=$query->fetch();
if (!$report) { http_response_code(404); exit('Incident not found or access not permitted.'); }
$pageTitle='Report #KW-'.$id;$currentPage=$role==='citizen'?'reports':'dashboard';$portalRole=$role;
require __DIR__ . '/includes/portal_header.php';
?>
<div class="page-heading"><div><span class="eyebrow">INCIDENT #KW-<?= (int)$report['id'] ?></span><h1>Incident details</h1><p>Recorded report information and latest status.</p></div><a class="text-back" href="<?= e(BASE_URL . dashboard_for($role)) ?>">← Back to dashboard</a></div>
<section class="surface-card detail-card"><div class="section-title"><div><h2><?= e((string)$report['incident_type']) ?></h2><p class="muted">Submitted <?= e(date('d M Y, H:i',strtotime((string)$report['created_at']))) ?></p></div><span class="status-pill <?= e(status_class((string)$report['status'])) ?>"><?= e((string)$report['status']) ?></span></div>
<div class="detail-grid"><div><span class="detail-label">County</span><strong><?= e((string)$report['county']) ?></strong></div><div><span class="detail-label">Location</span><strong><?= e((string)$report['location']) ?></strong></div><div><span class="detail-label">Incident date</span><strong><?= e(date('d M Y, H:i',strtotime((string)$report['incident_date']))) ?></strong></div><div><span class="detail-label">Case reference</span><strong>#KW-<?= (int)$report['id'] ?></strong></div>
<?php if ($role==='officer' || $role==='ranger'): ?><div><span class="detail-label">Reporter</span><strong><?= e((string)$report['reporter_name']) ?></strong></div><?php endif; ?>
<div><span class="detail-label">Assigned Ranger</span><strong><?= e((string)($report['ranger_name'] ?: 'Not yet assigned')) ?></strong></div></div><hr><h3>Description</h3><p class="detail-description"><?= nl2br(e((string)$report['description'])) ?></p>
<?php if ($report['photo_filename']): ?><h3>Uploaded evidence photo</h3><img class="detail-photo" alt="Photo uploaded with this incident report" src="<?= e(BASE_URL) ?>/incident_photo.php?id=<?= (int)$report['id'] ?>"><?php else: ?><p class="tiny-notice">No evidence photograph was uploaded for this incident.</p><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/portal_footer.php'; ?>
