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
    $imagePath = upload_game_image('image');

    if ($title === '') {
        $error = 'El título es obligatorio.';
    } else {
        $stmt = db()->prepare('INSERT INTO games (user_id, title, description, notes, image_path, status, progress, rating, release_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([current_user_id(), $title, $description, $notes, $imagePath, $status, $progress, $rating, $releaseYear]);
        redirect('index.php');
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
            <!-- Usamos el mismo archivo de formulario pero con tus estilos de Alumno 4 -->
            <?php require __DIR__ . '/game_form.php'; ?>
            
            <div class="form-actions">
                <button type="submit" class="btn-neon">GUARDAR EN BIBLIOTECA</button>
                <a href="index.php" class="btn-secondary">CANCELAR</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/inc/footer.php'; ?>