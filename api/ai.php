<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/ai_placeholders.php';

header('Content-Type: application/json; charset=utf-8');

if (!current_user_id()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Endpoint preparado, pero todavía sin IA real.
// action puede ser: description, image, year
$action = $_POST['action'] ?? '';
$title = trim($_POST['title'] ?? '');

$response = [
    'description' => '',
    'image_url' => '',
    'release_year' => null,
    'message' => 'Función de IA pendiente.'
];

echo json_encode($response);
