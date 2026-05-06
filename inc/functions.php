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
        return $oldImage;
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = mime_content_type($_FILES[$fieldName]['tmp_name']);

    if (!isset($allowed[$mime])) {
        return $oldImage;
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = UPLOAD_DIR . $filename;

    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
        return UPLOAD_URL . $filename;
    }

    return $oldImage;
}

function stars(?int $rating): string
{
    if (!$rating) {
        return 'Sin calificación';
    }

    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}
