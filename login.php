<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $username;
        redirect('index.php');
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}

require_once __DIR__ . '/inc/header.php';
?>

<main class="login-page">
    <div class="login-container">
        <h2 class="neon-text"><?= h(t('login')) ?></h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" class="gamer-form">
            <div class="input-group">
                <label class="neon-label"><?= h(t('username')) ?></label>
                <input type="text" name="username" required placeholder="Nick de jugador..." class="gamer-input">
            </div>

            <div class="input-group">
                <label class="neon-label"><?= h(t('password')) ?></label>
                <input type="password" name="password" required placeholder="********" class="gamer-input">
            </div>

            <button type="submit" class="btn-neon">
                <span class="btn-text">INICIAR SESIÓN</span>
            </button>
        </form>

        <div class="form-footer">
            <p>¿No tienes cuenta? <a href="register.php" class="neon-link">Regístrate aquí</a></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/inc/footer.php'; ?>