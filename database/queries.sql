USE game_tracker;

-- Consultar usuarios registrados
SELECT id, username, created_at
FROM users;

-- Verificar si un usuario ya existe
SELECT id
FROM users
WHERE username = '001';

-- Consultar juegos de un usuario
SELECT *
FROM games
WHERE user_id = 1
ORDER BY updated_at DESC;

-- Buscar juegos por título
SELECT *
FROM games
WHERE user_id = 1
AND title LIKE '%zelda%';

-- Ordenar juegos alfabéticamente
SELECT *
FROM games
WHERE user_id = 1
ORDER BY title ASC;

-- Ordenar juegos por fecha de creación
SELECT *
FROM games
WHERE user_id = 1
ORDER BY created_at DESC;

-- Insertar un juego
INSERT INTO games (
    user_id,
    title,
    description,
    notes,
    status,
    progress,
    rating,
    release_year
) VALUES (
    1,
    'The Legend of Zelda: Breath of the Wild',
    'Juego de aventura y mundo abierto.',
    'Pendiente de completar misiones secundarias.',
    'En progreso',
    45,
    5,
    2017
);

-- Actualizar información de un juego
UPDATE games
SET title = 'The Legend of Zelda: Breath of the Wild',
    description = 'Juego de aventura desarrollado por Nintendo.',
    notes = 'Explorar más santuarios.',
    status = 'En progreso',
    progress = 80,
    rating = 5,
    release_year = 2017
WHERE id = 1 AND user_id = 1;

-- Marcar juego como completado
UPDATE games
SET status = 'Completado',
    progress = 100
WHERE id = 1 AND user_id = 1;

-- Eliminar un juego
DELETE FROM games
WHERE id = 1 AND user_id = 1;