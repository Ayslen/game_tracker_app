<?php
/**
 *
 */
header('Content-Type: application/json');

$apiKey = "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"; 
$url = "https://api.groq.com/openai/v1/chat/completions";

$title = $_POST['title'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($title)) {
    echo json_encode(['status' => 'error', 'message' => 'Título vacío']);
    exit;
}

if ($action === 'get_year') {
    $prompt = "Año de lanzamiento de '$title'. Responde SOLO con los 4 números del año.";
} else {
    // Prompt específico para la descripción
    $prompt = "Escribe una sinopsis muy corta y emocionante del juego '$title' en español. Máximo 25 palabras. No uses introducciones como 'Este juego trata de...'";
}

$postData = json_encode([
    "model" => "llama-3.3-70b-versatile",
    "messages" => [
        ["role" => "system", "content" => "Eres un experto en videojuegos. Tus respuestas son directas y sin texto extra."],
        ["role" => "user", "content" => $prompt]
    ],
    "temperature" => 0.2 // Un poco más de creatividad para la descripción
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey]);

$response = curl_exec($ch);
$result = json_decode($response, true);
$aiText = $result['choices'][0]['message']['content'] ?? '';

echo json_encode(['status' => 'success', 'data' => trim($aiText)]);
