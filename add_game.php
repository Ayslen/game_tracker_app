<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $status = $_POST['status'] ?? 'Sin Iniciar';
    $progress = max(0, min(100, (int)($_POST['progress'] ?? 0)));
    $rating = ($_POST['rating'] ?? '') === '' ? null : max(1, min(5, (int)$_POST['rating']));
    $releaseYear = ($_POST['release_year'] ?? '') === '' ? null : (int)$_POST['release_year'];

    if ($title === '') {
        $error = 'El título es obligatorio.';
    } else {
        try {
            $imagePath = upload_game_image('image');

            $stmt = db()->prepare('
                INSERT INTO games (
                    user_id,
                    title,
                    description,
                    notes,
                    image_path,
                    status,
                    progress,
                    rating,
                    release_year
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                current_user_id(),
                $title,
                $description,
                $notes,
                $imagePath,
                $status,
                $progress,
                $rating,
                $releaseYear
            ]);

            redirect('index.php');
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}

require_once __DIR__ . '/inc/header.php';
?>

<main class="dashboard-main">
    <div class="form-container login-container">
        <h2 class="neon-text">AÑADIR NUEVO JUEGO</h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="gamer-form">
            <?php require __DIR__ . '/game_form.php'; ?>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn-neon">
                    <span class="btn-text">GUARDAR JUEGO</span>
                </button>
                <a href="index.php" class="btn-secondary" style="display:inline-block; margin-top:10px; text-decoration:none; color:#666;">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/inc/footer.php'; ?>