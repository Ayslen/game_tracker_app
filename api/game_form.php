<?php
require_once __DIR__ . '/inc/functions.php';
// Aquí iría el resto de tu lógica de carga de datos si es edición
?>

<h2>Añadir juego</h2>

<form id="form-juego" action="save_game.php" method="POST">
    <label>Título</label><br>
    <input type="text" name="title" id="title" placeholder="Ej: Sonic Frontiers"><br>

    <label>Descripción</label><br>
    <textarea name="description" id="description" rows="5" cols="40"></textarea><br>
    
    <button type="button" id="btn-ia-gemini">Generar descripción con IA</button><br>

    <label>Año de lanzamiento</label><br>
    <input type="text" name="release_year" id="release_year"><br>

    <button type="submit">Guardar Juego</button>
</form>

<script>
// Usamos un ID único para evitar que el navegador use funciones viejas
document.getElementById('btn-ia-gemini').onclick = async function() {
    const titleField = document.getElementById('title');
    const descField = document.getElementById('description');
    const yearField = document.getElementById('release_year');

    if (!titleField.value) {
        alert("Escribe un título primero.");
        return;
    }

    this.innerText = "Consultando a Gemini...";
    this.disabled = true;

    try {
        // Llamada directa al archivo PHP que ya configuramos
        const response = await fetch('api/ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'title=' + encodeURIComponent(titleField.value)
        });

        const data = await response.json();

        if (data.description) {
            descField.value = data.description;
            if(yearField) yearField.value = data.release_year;
        } else {
            alert("La IA no respondió correctamente. Revisa api/ai.php");
        }
    } catch (e) {
        alert("Error de conexión. Asegúrate de estar logueado.");
    } finally {
        this.innerText = "Generar descripción con IA";
        this.disabled = false;
    }
};
</script>