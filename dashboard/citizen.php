<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../includes/report_helpers.php';
require_role('citizen');
date_default_timezone_set('Africa/Nairobi');
$userId = (int)$_SESSION['user']['id'];
$summaryStmt = $pdo->prepare("SELECT COUNT(*) total,
 COALESCE(SUM(status='Pending'),0) pending,
 COALESCE(SUM(status='In Progress'),0) in_progress,
 COALESCE(SUM(status='Resolved'),0) resolved FROM incidents WHERE reporter_id=?");
$summaryStmt->execute([$userId]);
$summary = $summaryStmt->fetch();
$recentStmt = $pdo->prepare('SELECT id, incident_type, county, location, incident_date, status FROM incidents WHERE reporter_id=? ORDER BY created_at DESC LIMIT 4');
$recentStmt->execute([$userId]);
$recentReports = $recentStmt->fetchAll();
$typeStmt = $pdo->prepare('SELECT incident_type, COUNT(*) qty FROM incidents WHERE reporter_id=? GROUP BY incident_type ORDER BY qty DESC LIMIT 5');
$typeStmt->execute([$userId]);
$typeBreakdown = $typeStmt->fetchAll();
$hour = (int)date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
$firstName = strtok(trim((string)$_SESSION['user']['full_name']), ' ') ?: 'Citizen';
$pageTitle = 'Citizen Dashboard'; $currentPage = 'dashboard';
require __DIR__ . '/../includes/citizen_header.php';
?>
<section class="hero-dashboard hero-citizen" aria-labelledby="welcome-title">
 <div class="hero-copy"><span class="hero-kicker">KENYA WILDLIFEGUARD · CITIZEN PORTAL</span>
 <h1 id="welcome-title"><?= e($greeting) ?>, <?= e($firstName) ?>.</h1>
 <p>Report and track wildlife incidents in Kenya. Together, we can support safer communities and wildlife conservation.</p>
 <a href="<?= e(BASE_URL) ?>/report_incident.php" class="hero-action">+ Report a wildlife incident <span aria-hidden="true">→</span></a></div>
 <div class="hero-stamp" aria-hidden="true">✥ &nbsp; WildlifeGuard <span>Community reporting</span></div>
</section>
<?php if (!empty($_SESSION['flash_success'])): ?><div class="success-note" role="status"><?= e((string)$_SESSION['flash_success']) ?></div><?php unset($_SESSION['flash_success']); ?><?php endif; ?>
<section class="stats-grid" aria-label="Your real report statistics">
 <article class="stat-card red"><span class="stat-symbol" aria-hidden="true">▤</span><div><span class="stat-label">Total Reports</span><strong class="stat-value"><?= (int)$summary['total'] ?></strong><small>Your submitted reports</small></div></article>
 <article class="stat-card amber"><span class="stat-symbol" aria-hidden="true">◷</span><div><span class="stat-label">Pending</span><strong class="stat-value"><?= (int)$summary['pending'] ?></strong><small>Awaiting review</small></div></article>
 <article class="stat-card blue"><span class="stat-symbol" aria-hidden="true">↻</span><div><span class="stat-label">In Progress</span><strong class="stat-value"><?= (int)$summary['in_progress'] ?></strong><small>Being handled</small></div></article>
 <article class="stat-card green"><span class="stat-symbol" aria-hidden="true">✓</span><div><span class="stat-label">Resolved</span><strong class="stat-value"><?= (int)$summary['resolved'] ?></strong><small>Marked complete</small></div></article>
</section>
<div class="dashboard-split">
 <div class="dashboard-primary">
  <a href="<?= e(BASE_URL) ?>/report_incident.php" class="report-callout"><span class="callout-icon" aria-hidden="true">!</span><span><strong>Report Wildlife Incident</strong><small>Submit details of wildlife conflict in your area.</small></span><span class="callout-arrow" aria-hidden="true">→</span></a>
  <div class="quick-grid" aria-label="Quick links">
   <a class="quick-card" href="<?= e(BASE_URL) ?>/my_reports.php"><span class="quick-icon sky" aria-hidden="true">▤</span><strong>My Reports</strong><small>Review submitted incidents and status updates</small></a>
   <a class="quick-card" href="#wildlife-gallery"><span class="quick-icon mint" aria-hidden="true">♧</span><strong>Kenya's Wildlife</strong><small>Explore our wildlife photo gallery</small></a>
   <div class="quick-card upcoming"><span class="quick-icon gold" aria-hidden="true">⌖</span><strong>Conflict Hotspots</strong><small>Interactive incident mapping in a later sprint</small><span class="soon-tag">Coming soon</span></div>
   <div class="quick-card upcoming"><span class="quick-icon violet" aria-hidden="true">♧</span><strong>Alerts</strong><small>Community notifications in a later sprint</small><span class="soon-tag">Coming soon</span></div>
  </div>
  <section class="surface-card recent-card" aria-labelledby="recent-title"><div class="section-title"><h2 id="recent-title">My Reports <span class="heading-muted">· Recent activity</span></h2><a href="<?= e(BASE_URL) ?>/my_reports.php" class="small-link">View all →</a></div>
  <?php if (!$recentReports): ?>
   <div class="empty-state"><span aria-hidden="true">▤</span><h3>No reports yet</h3><p>After submitting a wildlife incident, its details and status will appear here.</p><a class="btn btn-primary" href="<?= e(BASE_URL) ?>/report_incident.php">Create your first report</a></div>
  <?php else: ?>
   <div class="recent-items">
   <?php foreach ($recentReports as $report): ?>
   <a class="recent-item" href="<?= e(BASE_URL) ?>/view_incident.php?id=<?= (int)$report['id'] ?>">
    <img loading="lazy" alt="Illustrative wildlife photograph" src="<?= e(BASE_URL) ?>/assets/wildlife/<?= e(wildlife_photo_for((string)$report['incident_type'])) ?>">
    <span class="recent-copy"><strong><?= e((string)$report['incident_type']) ?></strong><small><?= e((string)$report['county']) ?> · <?= e(date('d M Y', strtotime((string)$report['incident_date']))) ?></small></span>
    <span class="status-pill <?= e(status_class((string)$report['status'])) ?>"><?= e((string)$report['status']) ?></span><span class="recent-arrow" aria-hidden="true">›</span></a>
   <?php endforeach; ?>
   </div>
  <?php endif; ?>
  </section>
 </div>
 <div class="dashboard-secondary">
  <section class="surface-card map-preview" aria-labelledby="map-heading"><div class="section-title"><h2 id="map-heading">Incident Map</h2><span class="feature-tag">Planned feature</span></div><div class="map-photo"><span class="map-info">Map view will be added in a future development sprint. No live incident markers are displayed yet.</span></div></section>
  <section class="surface-card breakdown-card" aria-labelledby="types-heading"><div class="section-title"><h2 id="types-heading">Reports by Type</h2><span class="heading-muted">Your data</span></div>
  <?php if (!$typeBreakdown): ?><p class="subtle-note">Your incident categories will appear after you submit reports.</p>
  <?php else: ?>
    <?php foreach ($typeBreakdown as $row): $percent = (int)round((int)$row['qty'] * 100 / max(1,(int)$summary['total'])); ?>
    <div class="breakdown-line"><div><span><?= e((string)$row['incident_type']) ?></span><strong><?= (int)$row['qty'] ?></strong></div><div class="bar-track"><span style="width:<?= $percent ?>%"></span></div></div>
    <?php endforeach; ?>
  <?php endif; ?>
  </section>
  <article class="awareness-card"><img src="<?= e(BASE_URL) ?>/assets/wildlife/lion.webp" alt="Lion resting under a tree" loading="lazy"><div><span class="photo-kicker">WILDLIFE AWARENESS</span><h2>Coexistence is possible</h2><p>Stay at a safe distance, share accurate information, and report incidents responsibly.</p></div></article>
 </div>
</div>
<section id="wildlife-gallery" class="surface-card wildlife-gallery" aria-labelledby="gallery-title"><div class="section-title"><div><span class="eyebrow">KENYA'S NATURAL HERITAGE</span><h2 id="gallery-title">Explore Kenya's Wildlife</h2><p class="subtle-note">Photographs for awareness and appreciation—not live wildlife sightings.</p></div></div>
 <div class="gallery-grid">
 <?php foreach ([['Elephants','elephant-forest.webp'],['Lions','lion.webp'],['Zebras','zebras.webp'],['Wildebeest','wildebeest.webp'],['Giraffes','giraffes.webp'],['Antelopes','antelope.webp']] as [$label,$photo]): ?>
 <figure class="gallery-item"><img src="<?= e(BASE_URL) ?>/assets/wildlife/<?= e($photo) ?>" alt="<?= e($label) ?> in natural habitat" loading="lazy"><figcaption><?= e($label) ?></figcaption></figure>
 <?php endforeach; ?>
 </div>
</section>
<div class="eco-footer-note"><span class="eco-mark" aria-hidden="true">♧</span><div><strong>Help protect Kenya's wildlife</strong><p>Report responsibly and stay informed. In emergencies, contact the appropriate official wildlife response service.</p></div></div>
<?php require __DIR__ . '/../includes/citizen_footer.php'; ?>
