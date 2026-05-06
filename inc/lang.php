<?php
// Por ahora solo español está habilitado. Inglés queda como estructura preparada para después.
$LANG = $_SESSION['lang'] ?? 'es';

if (isset($_POST['lang'])) {
    $_SESSION['lang'] = $_POST['lang'] === 'en' ? 'en' : 'es';
    $LANG = $_SESSION['lang'];
}

$TEXT = [
    'es' => [
        'app_title' => 'Seguimiento de Juegos',
        'login' => 'Iniciar sesión',
        'register' => 'Registrarse',
        'logout' => 'Cerrar sesión',
        'username' => 'Usuario',
        'password' => 'Contraseña',
        'add_game' => 'Añadir juego',
        'edit_game' => 'Editar juego',
        'delete' => 'Borrar',
        'edit' => 'Editar',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        'title' => 'Título',
        'description' => 'Descripción',
        'notes' => 'Notas',
        'image' => 'Imagen',
        'status' => 'Estado',
        'progress' => 'Progreso',
        'rating' => 'Calificación',
        'release_year' => 'Año de lanzamiento',
        'search' => 'Buscar juego',
        'sort_by' => 'Ordenar por',
        'created_at' => 'Fecha de creación',
        'updated_at' => 'Fecha de edición',
        'alphabetical' => 'Alfabético',
        'ascending' => 'Ascendente',
        'descending' => 'Descendente',
        'load_more' => 'Cargar más',
        'confirm_delete' => '¿Seguro que deseas borrar este juego?',
        'ai_description' => 'Generar descripción con IA',
        'ai_image' => 'Buscar imagen con IA',
        'ai_year' => 'Detectar año con IA',
        'ai_pending' => 'Función de IA pendiente.',
    ],
    'en' => []
];

function t(string $key): string
{
    global $TEXT, $LANG;
    return $TEXT[$LANG][$key] ?? $TEXT['es'][$key] ?? $key;
}
