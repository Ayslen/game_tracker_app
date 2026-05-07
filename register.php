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
        // Regla: 8 caracteres y 2 números
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - GAME TRACKER</title>
    <link rel="stylesheet" href="Fronted/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <header class="top-branding">
        <h1 class="brand-title">Seguimiento de Juegos</h1>
    </header>

    <div class="login-container">
        <h2 class="neon-text">REGISTRO</h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" class="gamer-form">
            <label class="neon-label">Usuario</label>
            <input type="text" name="username" required placeholder="Crea tu nick...">
            
            <label class="neon-label" style="margin-top:15px; display:block;">Contraseña</label>
            <input type="password" name="password" required placeholder="Mín. 8 caracteres y 2 números">
            <small style="color:#888; font-size:0.8rem; display:block; margin-top:5px;">
                Seguridad: 8 caracteres y 2 números.
            </small>

            <button type="submit" class="btn-neon">CREAR CUENTA</button>
        </form>

        <div style="margin-top:20px;">
            <p>¿Ya tienes cuenta? <a href="login.php" style="color:var(--neon-blue); text-decoration:none; font-weight:bold;">Inicia sesión aquí</a></p>
        </div>
    </div>

    <?php require_once __DIR__ . '/inc/footer.php'; ?>
</body>
</html>