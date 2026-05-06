<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int)($_POST['id'] ?? 0);
$stmt = db()->prepare('DELETE FROM games WHERE id = ? AND user_id = ?');
$stmt->execute([$id, current_user_id()]);

redirect('index.php');
