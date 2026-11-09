<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
require_role('ranger');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid((string)($_POST['csrf_token'] ?? ''))) { http_response_code(403); exit('Invalid request.'); }
$id=filter_var($_POST['incident_id'] ?? null,FILTER_VALIDATE_INT);
$newStatus=(string)($_POST['status'] ?? '');
if (!$id || $id < 1 || !in_array($newStatus,['In Progress','Resolved'],true)) { $_SESSION['flash_error']='Invalid incident or status.'; redirect_to('/dashboard/ranger.php'); }
$stmt=$pdo->prepare("UPDATE incidents SET status=? WHERE id=? AND assigned_ranger_id=? AND status <> 'Resolved'");
$stmt->execute([$newStatus,$id,(int)$_SESSION['user']['id']]);
$_SESSION[$stmt->rowCount() ? 'flash_success' : 'flash_error']=$stmt->rowCount() ? 'Incident status updated.' : 'Cannot update this incident. Check your assignment or whether it is already resolved.';
redirect_to('/dashboard/ranger.php#assignments');
