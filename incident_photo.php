<?php
declare(strict_types=1);
require_once __DIR__ . '/config/auth.php';
if (!is_logged_in()) { http_response_code(403); exit; }
$role=(string)($_SESSION['user']['role'] ?? '');
if (!in_array($role,['citizen','ranger','officer'],true)) { http_response_code(403); exit; }
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); exit; }
$sql='SELECT photo_filename FROM incidents WHERE id=?';$params=[$id];
if ($role==='citizen') {$sql.=' AND reporter_id=?';$params[]=(int)$_SESSION['user']['id'];}
if ($role==='ranger') {$sql.=' AND assigned_ranger_id=?';$params[]=(int)$_SESSION['user']['id'];}
$sql.=' LIMIT 1';
$query=$pdo->prepare($sql);$query->execute($params);$filename=$query->fetchColumn();
if (!$filename || !preg_match('/^[a-f0-9]{40}\.bin$/',(string)$filename)) { http_response_code(404); exit; }
$path=__DIR__.'/private_uploads/'.$filename;
if (!is_file($path)) { http_response_code(404); exit; }
$mime=(new finfo(FILEINFO_MIME_TYPE))->file($path);
if (!in_array($mime,['image/jpeg','image/png','image/webp'],true)) { http_response_code(404); exit; }
header('Content-Type: '.$mime);header('Content-Length: '.filesize($path));header('Cache-Control: private, no-store');header('X-Content-Type-Options: nosniff');readfile($path);
