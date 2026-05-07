<?php
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function require_login(): void
{
    if (!current_user_id()) {
        redirect('login.php');
    }
}

function password_is_valid(string $password): bool
{
    if (strlen($password) < 8) {
        return false;
    }

    preg_match_all('/\d/', $password, $matches);
    return count($matches[0]) >= 2;
}

function upload_game_image(string $fieldName, ?string $oldImage = null): ?string
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImage;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'La imagen excede el tamaño máximo permitido por PHP.',
            UPLOAD_ERR_FORM_SIZE => 'La imagen excede el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'La imagen se subió de forma incompleta.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal de PHP.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar la imagen en el servidor.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP bloqueó la subida de la imagen.'
        ];

        $code = $_FILES[$fieldName]['error'];
        throw new RuntimeException($errors[$code] ?? 'Error desconocido al subir la imagen.');
    }

    $maxSize = 5 * 1024 * 1024; // 5 MB

    if ($_FILES[$fieldName]['size'] > $maxSize) {
        throw new RuntimeException('La imagen es demasiado pesada. Máximo permitido: 5 MB.');
    }

    $tmpName = $_FILES[$fieldName]['tmp_name'];
    $originalName = $_FILES[$fieldName]['name'];

    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowedExtensions = [
        'jpg' => 'jpg',
        'jpeg' => 'jpg',
        'jpe' => 'jpg',
        'jfif' => 'jpg',
        'png' => 'png',
        'webp' => 'webp',
        'gif' => 'gif',
        'avif' => 'avif'
    ];

    if (!isset($allowedExtensions[$extension])) {
        throw new RuntimeException('Formato no permitido. Usa JPG, JPEG, JFIF, PNG, WEBP, GIF o AVIF.');
    }

    $imageInfo = @getimagesize($tmpName);

    if ($imageInfo === false) {
        throw new RuntimeException('El archivo seleccionado no parece ser una imagen válida.');
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }

    if (!is_writable(UPLOAD_DIR)) {
        throw new RuntimeException('La carpeta uploads no tiene permisos de escritura.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowedExtensions[$extension];
    $destination = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('No se pudo mover la imagen a la carpeta uploads.');
    }

    return UPLOAD_URL . $filename;
}

function stars(?int $rating): string
{
    if (!$rating) {
        return 'Sin calificación';
    }

    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}