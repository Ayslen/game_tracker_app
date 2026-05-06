<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $error = 'Debes escribir un usuario.';
    } elseif (!password_is_valid($password)) {
        // Regla obligatoria: 8 caracteres y 2 números
        $error = 'La contraseña debe tener mínimo 8 caracteres y al menos 2 números.';
    } else {
        $stmt = db()->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Ese usuario ya existe.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = db()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            redirect('login.php');
        }
    }
}

require_once __DIR__ . '/inc/header.php';
?>

<main class="login-page">
    <div class="login-container">
        <h2 class="neon-text"><?= h(t('register')) ?></h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" id="registrationForm" class="gamer-form">
            <div class="input-group">
                <label class="neon-label"><?= h(t('username')) ?></label>
                <input type="text" name="username" required placeholder="Tu nuevo nick..." class="gamer-input">
            </div>

            <div class="input-group">
                <label class="neon-label"><?= h(t('password')) ?></label>
                <input type="password" name="password" id="register_password" required minlength="8" placeholder="Contraseña segura" class="gamer-input">
                <small class="helper-text">Mínimo 8 caracteres y al menos 2 números.</small>
            </div>

            <button type="submit" class="btn-neon">
                <span class="btn-text">CREAR CUENTA</span>
            </button>
        </form>

        <div class="form-footer">
            <p>¿Ya tienes cuenta? <a href="login.php" class="neon-link">Inicia sesión aquí</a></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/inc/footer.php'; ?>