<?php
require_once __DIR__ . '/../inc/functions.php';

header('Content-Type: application/json; charset=utf-8');

// Seguridad: Solo usuarios logueados
if (!current_user_id()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$title = trim($_POST['title'] ?? '');
// Tu API Key integrada
$apiKey = "AIzaSyB2muxr2Icd9HiYqP5TLoURJ78EgiyGMa8"; 

if (empty($title)) {
    echo json_encode(['error' => 'El título es obligatorio']);
    exit;
}

// Configuración para Gemini 2.5 Flash
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

$prompt = "Actúa como una enciclopedia de videojuegos. Para el título '$title', devuelve un JSON estrictamente con: 
'description' (español, máx 200 caracteres), 
'release_year' (año de lanzamiento), 
'image_query' (términos para buscar la portada oficial).";

$data = [
    "contents" => [["parts" => [["text" => $prompt]]]],
    "generationConfig" => [
        "response_mime_type" => "application/json"
    ]
];

// Llamada a la API mediante cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$result = curl_exec($ch);
curl_close($ch);

if ($result) {
    $decoded = json_decode($result, true);
    // Extraemos el JSON generado por la IA
    $ai_response = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    echo $ai_response; 
} else {
    echo json_encode(['error' => 'Error al conectar con la IA de Google']);
}