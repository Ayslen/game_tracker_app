<?php
// Configuración de base de datos.
// En XAMPP normalmente MySQL usa 3306.
// Si tu XAMPP usa 3307, cambia DB_PORT a 3307.

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'game_tracker');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME', 'Seguimiento de Juegos');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', 'uploads/');

session_start();
