CREATE DATABASE IF NOT EXISTS game_tracker
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE game_tracker;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS games (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
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

    CONSTRAINT fk_games_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_games_user_title (user_id, title),
    INDEX idx_games_user_created (user_id, created_at),
    INDEX idx_games_user_updated (user_id, updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;