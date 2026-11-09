<?php
require_once __DIR__ . '/config/auth.php';
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
?>
<section class="hero home-wildlife-hero">
 <span class="eyebrow">COMMUNITY REPORTING FOR KENYA</span>
 <h1>Protecting wildlife.<br>Supporting communities.</h1>
 <p class="lead">Kenya WildlifeGuard is an academic project for submitting human–wildlife conflict reports, tracking progress and coordinating a response.</p>
 <div class="actions"><?php if (is_logged_in()): ?><a class="btn" href="<?= e(BASE_URL . dashboard_for((string)$_SESSION['user']['role'])) ?>">Open my dashboard →</a><?php else: ?><a class="btn" href="<?= e(BASE_URL) ?>/register.php">Report an incident →</a><a class="btn btn-outline" href="<?= e(BASE_URL) ?>/login.php">Log in</a><?php endif; ?></div>
 <span class="hero-photo-note">Photo: Kenya's wildlife · Student project prototype</span>
</section>
<section class="home-intro"><span class="eyebrow">A CONNECTED REPORTING EXPERIENCE</span><h2>One platform. Three important roles.</h2><p>The system will connect citizen reporting, ranger follow-up and officer management through secure, role-based accounts.</p></section>
<section class="feature-grid wildlife-feature-grid" aria-label="User roles">
 <article class="feature-card"><img loading="lazy" src="<?= e(BASE_URL) ?>/assets/wildlife/elephant-forest.webp" alt="Elephant standing in vegetation"><span class="role-tag">CITIZEN</span><h2>Report</h2><p>Describe an incident, upload optional evidence and track the case status.</p></article>
 <article class="feature-card"><img loading="lazy" src="<?= e(BASE_URL) ?>/assets/wildlife/zebra-herd.webp" alt="Zebra herd in natural habitat"><span class="role-tag">RANGER</span><h2>Respond</h2><p>View assigned reports and update progress as cases are handled.</p></article>
 <article class="feature-card"><img loading="lazy" src="<?= e(BASE_URL) ?>/assets/wildlife/giraffes.webp" alt="Two giraffes in the savannah"><span class="role-tag">OFFICER</span><h2>Coordinate</h2><p>Manage incoming reports, assign ranger accounts and review statistics.</p></article>
</section>
<p class="home-disclaimer">Kenya WildlifeGuard is a student prototype and is not an official Kenya Wildlife Service portal or emergency reporting channel.</p>
<?php require __DIR__ . '/includes/footer.php'; ?>
