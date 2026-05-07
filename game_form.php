<div style="display: flex; gap: 15px; margin-bottom: 20px;">
    <div style="flex: 1;">
        <label style="color: #00f2ff; display: block; margin-bottom: 8px; font-weight: bold; text-transform: uppercase; font-size: 0.85em;">Estado actual</label>
        <select name="status" style="width: 100%; padding: 12px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px; cursor: pointer;">
            <option value="Sin Iniciar" <?= (isset($game['status']) && $game['status'] == 'Sin Iniciar') ? 'selected' : '' ?>>SIN INICIAR</option>
            <option value="En Curso" <?= (isset($game['status']) && $game['status'] == 'En Curso') ? 'selected' : '' ?>>EN CURSO</option>
            <option value="Completado" <?= (isset($game['status']) && $game['status'] == 'Completado') ? 'selected' : '' ?>>COMPLETADO</option>
            <option value="Abandonado" <?= (isset($game['status']) && $game['status'] == 'Abandonado') ? 'selected' : '' ?>>ABANDONADO</option>
        </select>
    </div>

    <div style="flex: 1;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <label style="color: #00f2ff; font-weight: bold; text-transform: uppercase; font-size: 0.85em;">Progreso: <span id="progress_label"><?= isset($game['progress']) ? $game['progress'] : '0' ?></span>%</label>
        </div>
        <input type="range" name="progress" id="progress_range" min="0" max="100" 
               value="<?= isset($game['progress']) ? $game['progress'] : '0' ?>" 
               style="width: 100%; cursor: pointer; accent-color: #00f2ff;">
    </div>
</div>

<div class="input-group" style="margin-bottom: 15px;">
    <label style="color: #00f2ff; font-weight: bold; display: block; margin-bottom: 5px; text-transform: uppercase; font-size: 0.85em;">Título del Juego</label>
    <input type="text" id="game_title" name="title" value="<?= isset($game['title']) ? htmlspecialchars($game['title']) : '' ?>" style="width: 100%; padding: 12px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px;" required placeholder="Escribe el titulo">
</div>

<div style="display: flex; gap: 15px; margin-bottom: 15px;">
    <div style="flex: 1;">
        <label style="color: #00f2ff; display: block; margin-bottom: 5px; text-transform: uppercase; font-size: 0.85em;">Año</label>
        <div style="display: flex; gap: 5px;">
            <input type="number" id="release_year" name="release_year" value="<?= isset($game['release_year']) ? $game['release_year'] : '' ?>" style="width: 70%; padding: 10px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px;">
            <button type="button" id="btn-year" style="background:#00f2ff; color:black; border:none; padding:10px; cursor:pointer; font-weight:bold; border-radius:4px; flex-grow: 1;">IA</button>
        </div>
    </div>
    <div style="flex: 1;">
        <label style="color: #00f2ff; display: block; margin-bottom: 5px; text-transform: uppercase; font-size: 0.85em;">Calificación</label>
        <div class="star-rating" style="font-size: 25px; cursor: pointer; display: flex; gap: 3px;">
            <?php $rating = isset($game['rating']) ? (int)$game['rating'] : 0; ?>
            <?php for($i=1; $i<=5; $i++): ?>
                <span class="star" data-value="<?= $i ?>" style="color: <?= $i <= $rating ? '#ffcc00' : '#444' ?>;">★</span>
            <?php endfor; ?>
        </div>
        <input type="hidden" name="rating" id="rating_value" value="<?= $rating ?>">
    </div>
</div>

<div class="input-group" style="margin-bottom: 15px;">
    <label style="color: #00f2ff; display: block; margin-bottom: 5px; text-transform: uppercase; font-size: 0.85em;">Descripción</label>
    <textarea id="description" name="description" rows="3" style="width: 100%; padding: 12px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px; resize: none;"><?= isset($game['description']) ? htmlspecialchars($game['description']) : '' ?></textarea>
    <button type="button" id="btn-desc" style="background:#00f2ff; color:black; border:none; padding:10px; margin-top:8px; cursor:pointer; font-weight:bold; border-radius:4px; width:100%; text-transform: uppercase;">IA GENERAR</button>
</div>

<div class="input-group" style="margin-bottom: 15px; background: #111; padding: 15px; border-radius: 8px; border: 1px solid #333;">
    <label style="color: #ff00ff; display: block; text-align: center; margin-bottom: 10px; font-weight: bold; text-transform: uppercase;">Portada del Juego</label>
    <div style="text-align: center; margin-bottom: 15px;">
        <img id="cover_preview" src="<?= isset($game['image_path']) && !empty($game['image_path']) ? htmlspecialchars($game['image_path']) : 'https://via.placeholder.com/150x200?text=SIN+IMAGEN' ?>" 
             style="width: 150px; height: 210px; object-fit: cover; border: 2px solid #ff00ff; border-radius: 8px;">
    </div>
    <input type="file" name="image" id="image_input" accept="image/*" style="color: #888; width: 100%;">
</div>

<script>
// ACTUALIZACIÓN DE BARRA DE PROGRESO EN TIEMPO REAL
const rangeInput = document.getElementById('progress_range');
const progressLabel = document.getElementById('progress_label');

rangeInput.addEventListener('input', function() {
    progressLabel.innerText = this.value;
});

// MANEJO DE ESTRELLAS
document.querySelectorAll('.star').forEach(star => {
    star.onclick = function() {
        const val = this.getAttribute('data-value');
        document.getElementById('rating_value').value = val;
        document.querySelectorAll('.star').forEach(s => {
            s.style.color = s.getAttribute('data-value') <= val ? '#ffcc00' : '#444';
        });
    };
});

// PREVISUALIZACIÓN DE IMAGEN
document.getElementById('image_input').onchange = function() {
    const [file] = this.files;
    if (file) {
        document.getElementById('cover_preview').src = URL.createObjectURL(file);
    }
};

// LÓGICA DE IA (Título -> Año/Descripción)
async function ejecutarIA(accion, idBoton) {
    const titulo = document.getElementById('game_title').value;
    if (!titulo) {
        alert("Escribe el nombre del juego primero");
        return;
    }

    const btn = document.getElementById(idBoton);
    const originalText = btn.innerText;
    btn.innerText = "...";

    try {
        const response = await fetch('api/ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `title=${encodeURIComponent(titulo)}&action=${accion}`
        });

        const result = await response.json();
        if (result.status === 'success') {
            if (accion === 'get_year') {
                document.getElementById('release_year').value = result.data.replace(/\D/g, '');
            } else if (accion === 'get_desc') {
                document.getElementById('description').value = result.data;
            }
        }
    } catch (error) {
        console.error("Error IA:", error);
    } finally {
        btn.innerText = originalText;
    }
}

document.getElementById('btn-year').onclick = () => ejecutarIA('get_year', 'btn-year');
document.getElementById('btn-desc').onclick = () => ejecutarIA('get_desc', 'btn-desc');
</script>
