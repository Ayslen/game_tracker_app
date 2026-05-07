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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GAME TRACKER</title>
    <link rel="stylesheet" href="Fronted/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">

    <header class="top-branding">
        <h1 class="brand-title">Seguimiento de Juegos</h1>
    </header>

    <div class="login-container">
        <h2 class="neon-text">INICIAR SESIÓN</h2>

        <?php if ($error): ?>
            <p class="error-log" style="display:block;"><?= h($error) ?></p>
        <?php endif; ?>

        <form method="post" class="gamer-form">
            <label class="neon-label">Usuario</label>
            <input type="text" name="username" required placeholder="Nick de jugador...">

            <label class="neon-label" style="margin-top:15px; display:block;">Contraseña</label>
            <input type="password" name="password" required placeholder="********">

            <button type="submit" class="btn-neon">ENTRAR</button>
        </form>

        <div style="margin-top:20px;">
            <p>¿Eres nuevo? <a href="register.php" style="color:var(--neon-blue); text-decoration:none; font-weight:bold;">Regístrate aquí</a></p>
        </div>
    </div>

    <?php require_once __DIR__ . '/inc/footer.php'; ?>
</body>
</html>