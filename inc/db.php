<?php
require_once __DIR__ . '/../config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $host = DB_HOST;
    $port = defined('DB_PORT') ? DB_PORT : '3306';
    $charset = DB_CHARSET;
    $dbName = DB_NAME;
    $safeDbName = str_replace('`', '``', $dbName);

    try {
        // Primero conecta al servidor MySQL sin seleccionar base de datos.
        $serverDsn = "mysql:host={$host};port={$port};charset={$charset}";
        $serverPdo = new PDO($serverDsn, DB_USER, DB_PASS, $options);

        // Crea la base de datos automáticamente si todavía no existe.
        $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$safeDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Ahora conecta ya dentro de la base de datos.
        $dbDsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
        $pdo = new PDO($dbDsn, DB_USER, DB_PASS, $options);

        create_tables($pdo);
    } catch (PDOException $e) {
        die(
            '<h2>Error de conexión a MySQL</h2>' .
            '<p>Revisa que MySQL esté iniciado en XAMPP y que el puerto en <strong>config.php</strong> sea correcto.</p>' .
            '<p>Puerto configurado: <strong>' . htmlspecialchars((string)$port) . '</strong></p>' .
            '<p>Mensaje técnico: ' . htmlspecialchars($e->getMessage()) . '</p>'
        );
    }

    return $pdo;
}

function create_tables(PDO $pdo): void
{
    // Tabla de Usuarios
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Agregamos UNSIGNED
        username VARCHAR(80) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Tabla de Juegos
    $pdo->exec("CREATE TABLE IF NOT EXISTS games (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- Agregamos UNSIGNED
        user_id INT UNSIGNED NOT NULL,              -- Debe ser UNSIGNED para coincidir con users.id
        title VARCHAR(150) NOT NULL,
        description TEXT NULL,
        notes TEXT NULL,
        image_path VARCHAR(255) NULL,
        status ENUM('Sin Iniciar', 'En progreso', 'Completado', 'Abandonado') NOT NULL DEFAULT 'Sin Iniciar',
        progress TINYINT UNSIGNED NOT NULL DEFAULT 0,
        rating TINYINT UNSIGNED NULL,
        release_year SMALLINT UNSIGNED NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        -- La relación ahora sí funcionará porque ambos son INT UNSIGNED
        CONSTRAINT fk_games_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_games_user_title (user_id, title),
        INDEX idx_games_user_created (user_id, created_at),
        INDEX idx_games_user_updated (user_id, updated_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

