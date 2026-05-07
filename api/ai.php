<?php
/**
 *
 */
header('Content-Type: application/json');

// 1. Configuración de Groq con tu nueva API Key
$apiKey = "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"; 
$url = "https://api.groq.com/openai/v1/chat/completions";

$title = $_POST['title'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($title)) {
    echo json_encode(['status' => 'error', 'message' => 'Escribe el nombre del juego']);
    exit;
}

// 2. Definición del Prompt
$prompt = ($action === 'get_year') 
    ? "Dame solo el año de lanzamiento de $title en 4 dígitos, sin texto adicional." 
    : "Escribe una sinopsis corta de $title en español (máximo 25 palabras).";

// 3. Estructura del JSON para Groq
$postData = json_encode([
    "model" => "llama-3.3-70b-versatile", // Modelo de alto rendimiento
    "messages" => [
        [
            "role" => "system",
            "content" => "Eres un asistente especializado en videojuegos para el TecNL."
        ],
        [
            "role" => "user",
            "content" => $prompt
        ]
    ],
    "temperature" => 0.6
]);

// 4. Petición cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(curl_errno($ch)){
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);

// 5. Manejo de respuesta de Groq
if ($httpCode !== 200) {
    $errorMsg = $result['error']['message'] ?? 'Error desconocido';
    echo json_encode(['status' => 'error', 'message' => 'Groq Error: ' . $errorMsg]);
    exit;
}

$aiText = $result['choices'][0]['message']['content'] ?? 'No se encontró información';

// 6. Respuesta para el Frontend
echo json_encode([
    'status' => 'success', 
    'data' => trim($aiText)
]);
