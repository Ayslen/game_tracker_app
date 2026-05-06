<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/lang.php';

header('Content-Type: application/json; charset=utf-8');

if (!current_user_id()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;
$q = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'updated_at';
$order = strtolower($_GET['order'] ?? 'desc');

$allowedSorts = [
    'created_at' => 'created_at',
    'updated_at' => 'updated_at',
    'title' => 'title'
];

$allowedOrders = ['asc', 'desc'];

$sortSql = $allowedSorts[$sort] ?? 'updated_at';
$orderSql = in_array($order, $allowedOrders, true) ? strtoupper($order) : 'DESC';

$params = [current_user_id()];
$where = 'WHERE user_id = ?';

if ($q !== '') {
    $where .= ' AND title LIKE ?';
    $params[] = '%' . $q . '%';
}

$sql = "SELECT * FROM games $where ORDER BY $sortSql $orderSql LIMIT $limit OFFSET $offset";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$games = $stmt->fetchAll();

foreach ($games as &$game) {
    $game['safe_title'] = h($game['title']);
    $game['safe_description'] = h($game['description']);
    $game['safe_notes'] = h($game['notes']);
    $game['stars'] = stars($game['rating'] ? (int)$game['rating'] : null);
}

echo json_encode([
    'games' => $games,
    'page' => $page,
    'has_more' => count($games) === $limit
]);
