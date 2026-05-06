<?php require_once __DIR__ . '/lang.php'; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h(APP_NAME) ?></title>
</head>
<body>
<header>
    <h1><?= h(t('app_title')) ?></h1>
    <nav>
        <?php if (current_user_id()): ?>
            <a href="index.php">Inicio</a> |
            <a href="add_game.php">+ <?= h(t('add_game')) ?></a> |
            <a href="logout.php"><?= h(t('logout')) ?></a>
        <?php else: ?>
            <a href="login.php"><?= h(t('login')) ?></a> |
            <a href="register.php"><?= h(t('register')) ?></a>
        <?php endif; ?>
    </nav>

    <form method="post">
        <label>Idioma:</label>
        <select name="lang" onchange="this.form.submit()">
            <option value="es" <?= ($LANG === 'es') ? 'selected' : '' ?>>Español</option>
            <option value="en" <?= ($LANG === 'en') ? 'selected' : '' ?>>English pendiente</option>
        </select>
    </form>
    <hr>
</header>
