USE game_tracker;

-- Usuario de prueba:
-- Usuario: demo
-- Contraseña: demo1234

INSERT INTO users (username, password_hash)
VALUES (
    'demo',
    '$2y$12$/r/4Pi0UEHBTEkOXm/941OePARG6FYLjBi2BR8VmIDyovCyrbSTRW'
)
ON DUPLICATE KEY UPDATE username = username;

SET @demo_user_id := (
    SELECT id FROM users WHERE username = 'demo' LIMIT 1
);

-- Limpia juegos anteriores del usuario demo para evitar duplicados si se importa más de una vez
DELETE FROM games WHERE user_id = @demo_user_id;

INSERT INTO games (
    user_id,
    title,
    description,
    notes,
    image_path,
    status,
    progress,
    rating,
    release_year
) VALUES
(
    @demo_user_id,
    'Minecraft',
    'Juego de construcción, exploración y supervivencia.',
    'Crear una base principal y explorar el Nether.',
    'uploads/demo/minecraft.jpg',
    'En progreso',
    60,
    5,
    2011
),
(
    @demo_user_id,
    'Grand Theft Auto V',
    'Juego de acción y mundo abierto desarrollado por Rockstar Games.',
    'Terminar misiones secundarias.',
    'uploads/demo/gta-v.jpg',
    'Completado',
    100,
    4,
    2013
),
(
    @demo_user_id,
    'Elden Ring',
    'Juego de rol y acción en mundo abierto.',
    'Pendiente vencer varios jefes opcionales.',
    'uploads/demo/elden-ring.jpg',
    'En progreso',
    35,
    5,
    2022
),
(
    @demo_user_id,
    'Resident Evil 9: Requiem',
    'Juego de terror y supervivencia de la saga Resident Evil.',
    'Juego completado al 100%.',
    'uploads/demo/re9-requiem.jpg',
    'Completado',
    100,
    5,
    2026
);