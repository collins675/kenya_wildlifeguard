<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
require_role('officer');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid((string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Invalid request.'); }
$id=filter_var($_POST['incident_id'] ?? null,FILTER_VALIDATE_INT);
$rangerId=filter_var($_POST['ranger_id'] ?? null,FILTER_VALIDATE_INT);
if (!$id || !$rangerId || $id<1 || $rangerId<1) { $_SESSION['flash_error']='Select an incident and a ranger.'; redirect_to('/dashboard/officer.php'); }
$verify=$pdo->prepare("SELECT id FROM users WHERE id=? AND role='ranger' LIMIT 1");
$verify->execute([$rangerId]);
if (!$verify->fetchColumn()) { $_SESSION['flash_error']='Selected account is not a ranger.'; redirect_to('/dashboard/officer.php'); }
$stmt=$pdo->prepare("UPDATE incidents SET assigned_ranger_id=?, status='In Progress' WHERE id=? AND status <> 'Resolved'");
$stmt->execute([$rangerId,$id]);
$_SESSION[$stmt->rowCount() ? 'flash_success' : 'flash_error']=$stmt->rowCount() ? 'Incident assigned to the selected ranger.' : 'This incident cannot be assigned (it may already be resolved).';
redirect_to('/dashboard/officer.php#cases');
