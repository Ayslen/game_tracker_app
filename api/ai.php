<?php

if (file_exists(__DIR__ . '/../inc/functions.php')) {
    require_once __DIR__ . '/../inc/functions.php';
}

header('Content-Type: application/json');


// --- CONFIGURACIÓN ---
$geminiKey = 'AIzaSyB2muxr2Icd9HiYqP5TLoURJ78EgiyGMa8'; 
$title = $_POST['title'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($title)) {
    echo json_encode(['error' => 'No se recibió el título']);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $geminiKey;

// Elegir el prompt
if ($action === 'get_year') {
    $prompt = "Videojuego: " . $title . ". Responde SOLO el año de lanzamiento (4 números).";
} else {
    $prompt = "Videojuego: " . $title . ". Dame una descripción breve en español (2 líneas).";
}

$data = ["contents" => [["parts" => [["text" => $prompt]]]]];

// Llamada a Google
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode !== 200) {
    $msg = $result['error']['message'] ?? 'Error en la API';
    echo json_encode(['error' => 'Google dice: ' . $msg]);
    exit;
}

$aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

if ($action === 'get_year') {
    $year = preg_replace('/[^0-9]/', '', $aiText);
    echo json_encode(['release_year' => substr($year, 0, 4)]);
} else {
    echo json_encode(['description' => trim($aiText)]);
}
exit;
