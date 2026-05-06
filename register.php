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

<h2><?= h(t('register')) ?></h2>

<?php if ($error): ?>
    <p><?= h($error) ?></p>
<?php endif; ?>

<form method="post">
    <label><?= h(t('username')) ?></label><br>
    <input type="text" name="username" required><br><br>

    <label><?= h(t('password')) ?></label><br>
    <input type="password" name="password" required minlength="8"><br>
    <small>Mínimo 8 caracteres y al menos 2 números.</small><br><br>

    <button type="submit"><?= h(t('register')) ?></button>
</form>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
