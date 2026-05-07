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

<script>
// Esta función ahora coincide con los IDs de game_form.php
async function ejecutarIA(accion, idBoton) {
    const elTitulo = document.getElementById('game_title');
    const elYear = document.getElementById('release_year');
    const elDesc = document.getElementById('description');
    
    if (!elTitulo || !elTitulo.value) {
        alert("Escribe el nombre del juego primero");
        return;
    }

    const btn = document.getElementById(idBoton);
    const originalText = btn.innerText;
    btn.innerText = "...";

    try {
        const response = await fetch('api/ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `title=${encodeURIComponent(elTitulo.value)}&action=${accion}`
        });

        const result = await response.json();

        if (result.status === 'success') {
            if (accion === 'get_year') {
                elYear.value = result.data.replace(/\D/g, '');
            } else if (accion === 'get_desc') {
                elDesc.value = result.data;
            }
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        alert("Asegúrate de usar http://localhost/practica/");
    } finally {
        btn.innerText = originalText;
    }
}

// Vinculamos los botones que están dentro de game_form.php
document.getElementById('btn-year').onclick = () => ejecutarIA('get_year', 'btn-year');
document.getElementById('btn-desc').onclick = () => ejecutarIA('get_desc', 'btn-desc');
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
