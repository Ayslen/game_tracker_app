<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM games WHERE id = ? AND user_id = ?');
$stmt->execute([$id, current_user_id()]);
$game = $stmt->fetch();

if (!$game) {
    die('Juego no encontrado.');
}

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
            $imagePath = upload_game_image('image', $game['image_path']);

            $stmt = db()->prepare('
                UPDATE games
                SET title = ?,
                    description = ?,
                    notes = ?,
                    image_path = ?,
                    status = ?,
                    progress = ?,
                    rating = ?,
                    release_year = ?
                WHERE id = ? AND user_id = ?
            ');

            $stmt->execute([
                $title,
                $description,
                $notes,
                $imagePath,
                $status,
                $progress,
                $rating,
                $releaseYear,
                $id,
                current_user_id()
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
        <h2 class="neon-text"><?= h(t('edit_game')) ?></h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="gamer-form">
            <?php require __DIR__ . '/game_form.php'; ?>

            <div class="form-actions">
                <button type="submit" class="btn-neon-save">ACTUALIZAR</button>
                <a href="index.php" class="btn-cancel">VOLVER</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/inc/footer.php'; ?>