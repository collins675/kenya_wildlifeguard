<?php
// Shared dashboard layout. Each page sets $portalRole, $pageTitle, $currentPage.
$portalRole = $portalRole ?? 'citizen';
$pageTitle = $pageTitle ?? 'Dashboard';
$currentPage = $currentPage ?? 'dashboard';
$fullName = (string) ($_SESSION['user']['full_name'] ?? 'User');
$firstName = strtok(trim($fullName), ' ') ?: 'User';
$initial = function_exists('mb_substr') ? mb_strtoupper(mb_substr($firstName, 0, 1)) : strtoupper(substr($firstName, 0, 1));
$portalName = ['citizen'=>'Citizen', 'ranger'=>'Ranger', 'officer'=>'Officer'][$portalRole] ?? 'Citizen';
$dashboardPath = dashboard_for($portalRole);
$menus = [
  'citizen' => [
    ['dashboard','⌂','Dashboard', $dashboardPath],
    ['report','△','Report Incident','/report_incident.php'],
    ['reports','▤','My Reports','/my_reports.php'],
    ['wildlife','♧','Wildlife Gallery','/dashboard/citizen.php#wildlife-gallery'],
  ],
  'ranger' => [
    ['dashboard','⌂','Overview',$dashboardPath],
    ['assignments','▤','My Assignments',$dashboardPath . '#assignments'],
    ['guidance','✓','Status Guide',$dashboardPath . '#status-guide'],
  ],
  'officer' => [
    ['dashboard','⌂','Overview',$dashboardPath],
    ['cases','▤','Manage Incidents',$dashboardPath . '#cases'],
    ['rangers','♧','Ranger Team',$dashboardPath . '#rangers'],
  ],
];
?>
<!doctype html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<title><?= e($pageTitle) ?> | Kenya WildlifeGuard</title>
<link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/citizen.css?v=4">
<link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/backgrounds.css?v=1">
</head><body>
<div class="app-shell">
<aside class="sidebar" aria-label="<?= e($portalName) ?> navigation">
  <a class="sidebar-brand" href="<?= e(BASE_URL) ?>/index.php"><span class="brand-symbol" aria-hidden="true">✥</span><span><strong>WildlifeGuard</strong><small>🇰🇪 &nbsp; Kenya</small></span></a>
  <div class="side-profile"><span class="avatar side-avatar"><?= e($initial) ?></span><span><strong><?= e($fullName) ?></strong><small><?= e($portalName) ?> account</small></span></div>
  <div class="menu-label">YOUR WORKSPACE</div>
  <nav class="side-nav" aria-label="Main menu">
   <?php foreach ($menus[$portalRole] as [$key,$symbol,$name,$path]): ?>
   <a class="<?= $currentPage === $key ? 'active' : '' ?>" <?= $currentPage === $key ? 'aria-current="page"' : '' ?> href="<?= e(BASE_URL . $path) ?>"><span class="nav-icon" aria-hidden="true"><?= e($symbol) ?></span><?= e($name) ?></a>
   <?php endforeach; ?>
  </nav>
  <div class="sidebar-spacer"></div>
  <div class="sidebar-photo"><span>Protecting wildlife,<br>supporting communities.</span></div>
  <div class="sidebar-bottom"><form action="<?= e(BASE_URL) ?>/logout.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="logout-button" type="submit"><span aria-hidden="true">⇥</span> Log out</button></form></div>
</aside>
<main class="main-panel role-<?= e($portalRole) ?>" id="main-content">
<header class="topbar"><a href="<?= e(BASE_URL . $dashboardPath) ?>" class="mobile-brand"><span class="topbar-brand-icon">✥</span><span><strong>Kenya WildlifeGuard</strong><small><?= e($portalName) ?> dashboard</small></span></a><div class="topbar-right"><span class="prototype-chip">Academic prototype</span><span class="avatar header-avatar" title="<?= e($fullName) ?>"><?= e($initial) ?></span></div></header>
<div class="page-content">
