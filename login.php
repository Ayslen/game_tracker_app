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

<h2><?= h(t('login')) ?></h2>

<?php if ($error): ?>
    <p><?= h($error) ?></p>
<?php endif; ?>

<form method="post">
    <label><?= h(t('username')) ?></label><br>
    <input type="text" name="username" required><br><br>

    <label><?= h(t('password')) ?></label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit"><?= h(t('login')) ?></button>
</form>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
