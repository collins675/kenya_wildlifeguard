<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
require_role('citizen');
date_default_timezone_set('Africa/Nairobi');

$types = ['Elephant conflict','Crop damage','Livestock attack','Wildlife sighting near homes','Property damage','Other wildlife conflict'];
$errors = [];
$values = [
    'incident_type' => '', 'county' => '', 'location' => '',
    'incident_date' => date('Y-m-d\TH:i'), 'description' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $_) {
        $values[$key] = trim((string) ($_POST[$key] ?? ''));
    }
    if (!csrf_valid((string) ($_POST['csrf_token'] ?? ''))) $errors[] = 'Your session expired. Refresh and try again.';
    if (!in_array($values['incident_type'], $types, true)) $errors[] = 'Choose a valid incident type.';
    if (strlen($values['county']) < 2 || strlen($values['county']) > 100) $errors[] = 'Enter a county name (2 to 100 characters).';
    if (strlen($values['location']) < 3 || strlen($values['location']) > 160) $errors[] = 'Enter a location (3 to 160 characters).';
    if (strlen($values['description']) < 20 || strlen($values['description']) > 3000) $errors[] = 'Describe the incident in 20 to 3000 characters.';
    $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $values['incident_date']);
    if (!$parsedDate || $parsedDate->format('Y-m-d\TH:i') !== $values['incident_date']) {
        $errors[] = 'Enter a valid incident date and time.';
    } elseif ($parsedDate > new DateTimeImmutable('+5 minutes')) {
        $errors[] = 'The incident date cannot be in the future.';
    }

    $photoFilename = null;
    $photoTmp = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['photo'];
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'Photo upload failed. Please choose the file again.';
        } elseif ($file['size'] > 3 * 1024 * 1024 || $file['size'] < 1) {
            $errors[] = 'The photo must be smaller than 3 MB.';
        } else {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
            $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
            if (!isset($allowed[$mime]) || getimagesize($file['tmp_name']) === false) {
                $errors[] = 'Upload a valid JPG, PNG or WebP image.';
            } else {
                $photoTmp = $file['tmp_name'];
            }
        }
    }

    if (!$errors) {
        $photoDirectory = __DIR__ . '/private_uploads';
        if ($photoTmp !== null) {
            if (!is_dir($photoDirectory) && !mkdir($photoDirectory, 0700, true)) {
                $errors[] = 'Could not create the photo storage directory.';
            } else {
                $photoFilename = bin2hex(random_bytes(20)) . '.bin';
                if (!move_uploaded_file($photoTmp, $photoDirectory . '/' . $photoFilename)) {
                    $errors[] = 'Could not save the photo.';
                }
            }
        }
        if (!$errors) {
            try {
                $insert = $pdo->prepare('INSERT INTO incidents
                    (reporter_id, incident_type, county, location, incident_date, description, photo_filename)
                    VALUES (:reporter_id, :incident_type, :county, :location, :incident_date, :description, :photo_filename)');
                $insert->execute([
                    'reporter_id' => (int) $_SESSION['user']['id'],
                    'incident_type' => $values['incident_type'],
                    'county' => $values['county'],
                    'location' => $values['location'],
                    'incident_date' => $parsedDate->format('Y-m-d H:i:s'),
                    'description' => $values['description'],
                    'photo_filename' => $photoFilename,
                ]);
                $_SESSION['flash_success'] = 'Incident report submitted successfully. You can track it below.';
                redirect_to('/my_reports.php');
            } catch (PDOException $exception) {
                if ($photoFilename !== null) @unlink($photoDirectory . '/' . $photoFilename);
                error_log('WildlifeGuard incident insert: ' . $exception->getMessage());
                $errors[] = 'Could not save the report. Check that you ran database/incidents.sql.';
            }
        }
    }
}
$pageTitle = 'Report a Wildlife Incident';
$currentPage = 'report';
require __DIR__ . '/includes/citizen_header.php';
?>
<div class="page-heading"><div><span class="eyebrow">CITIZEN PORTAL</span><h1>Report wildlife incident</h1><p>Help us document an incident by providing accurate details below.</p></div><a class="text-back" href="<?= e(BASE_URL) ?>/dashboard/citizen.php">← Back to dashboard</a></div>
<div class="form-layout"><section class="surface-card form-surface">
    <div class="section-title"><div><span class="eyebrow">NEW REPORT</span><h2>Incident details</h2></div><span class="required-note">* Required fields</span></div>
    <?php if ($errors): ?><div class="error-note" role="alert"><strong>Please correct:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form action="<?= e(BASE_URL) ?>/report_incident.php" method="post" enctype="multipart/form-data" class="incident-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="field"><label for="incident_type">Incident type <span>*</span></label><select id="incident_type" name="incident_type" required><option value="">Select incident type</option><?php foreach ($types as $type): ?><option value="<?= e($type) ?>" <?= $values['incident_type'] === $type ? 'selected' : '' ?>><?= e($type) ?></option><?php endforeach; ?></select></div>
        <div class="field-row"><div class="field"><label for="county">County <span>*</span></label><input id="county" name="county" type="text" value="<?= e($values['county']) ?>" placeholder="e.g. Machakos" maxlength="100" required></div><div class="field"><label for="location">Location or nearest landmark <span>*</span></label><input id="location" name="location" type="text" value="<?= e($values['location']) ?>" placeholder="e.g. Mlolongo" maxlength="160" required></div></div>
        <div class="field"><label for="incident_date">Date and time of incident <span>*</span></label><input id="incident_date" name="incident_date" type="datetime-local" value="<?= e($values['incident_date']) ?>" required></div>
        <div class="field"><label for="description">Description <span>*</span></label><textarea id="description" name="description" rows="6" minlength="20" maxlength="3000" placeholder="What happened? Which wildlife was involved? Provide relevant details without putting yourself at risk." required><?= e($values['description']) ?></textarea><small>At least 20 characters. Avoid sharing private identification numbers.</small></div>
        <div class="field"><label for="photo">Upload photo <small>(optional)</small></label><input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG or WebP, up to 3 MB. Only upload photos you have permission to share.</small></div>
        <div class="form-actions"><button class="btn btn-primary" type="submit">Submit incident report →</button><a class="btn btn-secondary" href="<?= e(BASE_URL) ?>/dashboard/citizen.php">Cancel</a></div>
    </form>
</section><aside class="surface-card helper-panel"><img class="helper-photo" src="<?= e(BASE_URL) ?>/assets/wildlife/elephant-forest.webp" alt="Elephant in green woodland"><span class="helper-icon">🌿</span><h2>Safety first</h2><p>Keep a safe distance from wildlife. Do not approach, chase or attempt to handle an animal to gather evidence.</p><p class="helper-small">This is a student project prototype. For an urgent wildlife incident, use official emergency reporting channels rather than relying on this site.</p></aside></div>
<?php require __DIR__ . '/includes/citizen_footer.php'; ?>
