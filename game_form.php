<div class="input-group" style="margin-bottom: 15px;">
    <label style="color: #00f2ff; font-weight: bold; display: block; margin-bottom: 5px;">Título del Juego</label>
    <input type="text" id="game_title" name="title" value="<?= isset($game['title']) ? htmlspecialchars($game['title']) : '' ?>" style="width: 100%; padding: 12px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px;" required placeholder="Escribe el titulo">
</div>

<div style="display: flex; gap: 15px; margin-bottom: 15px;">
    <div style="flex: 1;">
        <label style="color: #00f2ff; display: block; margin-bottom: 5px;">Año</label>
        <div style="display: flex; gap: 5px;">
            <input type="number" id="release_year" name="release_year" value="<?= isset($game['release_year']) ? $game['release_year'] : '' ?>" style="width: 70%; padding: 10px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px;">
            <button type="button" id="btn-year" style="background:#00f2ff; color:black; border:none; padding:10px; cursor:pointer; font-weight:bold; border-radius:4px;">📅 IA</button>
        </div>
    </div>
    <div style="flex: 1;">
        <label style="color: #00f2ff; display: block; margin-bottom: 5px;">Calificación</label>
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
    <label style="color: #00f2ff; display: block; margin-bottom: 5px;">Descripción</label>
    <textarea id="description" name="description" rows="3" style="width: 100%; padding: 12px; background: #1a1a1a; color: white; border: 1px solid #333; border-radius: 4px;"><?= isset($game['description']) ? htmlspecialchars($game['description']) : '' ?></textarea>
    <button type="button" id="btn-desc" style="background:#00ff00; color:black; border:none; padding:10px; margin-top:8px; cursor:pointer; font-weight:bold; border-radius:4px; width:100%;">📝 GENERAR CON IA</button>
</div>

<div class="input-group" style="margin-bottom: 15px; background: #111; padding: 15px; border-radius: 8px; border: 1px solid #333;">
    <label style="color: #ff00ff; display: block; text-align: center; margin-bottom: 10px; font-weight: bold;">PORTADA DEL JUEGO</label>
    
    <div style="text-align: center; margin-bottom: 15px;">
        <img id="cover_preview" src="<?= isset($game['image']) && !empty($game['image']) ? 'uploads/'.$game['image'] : 'https://via.placeholder.com/150x200?text=SIN+IMAGEN' ?>" 
             style="width: 150px; height: 210px; object-fit: cover; border: 2px solid #ff00ff; border-radius: 8px;">
    </div>

    <label style="color: #aaa; font-size: 0.8em; display: block; margin-bottom: 5px;">Selecciona el archivo de portada:</label>
    <input type="file" name="image" id="image_input" accept="image/*" style="color: #888; width: 100%;" required>
</div>

<script>
// --- 1. ESTRELLAS -
document.querySelectorAll('.star').forEach(star => {
    star.onclick = function() {
        const val = this.getAttribute('data-value');
        document.getElementById('rating_value').value = val;
        document.querySelectorAll('.star').forEach(s => {
            s.style.color = s.getAttribute('data-value') <= val ? '#ffcc00' : '#444';
        });
    };
});

// --- 2. PREVISUALIZACIÓN DE ARCHIVO ---
document.getElementById('image_input').onchange = function() {
    const [file] = this.files;
    if (file) {
        document.getElementById('cover_preview').src = URL.createObjectURL(file);
    }
};

// --- 3. LÓGICA DE LA IA ---
async function ejecutarIA(accion, idBoton) {
    const titulo = document.getElementById('game_title').value;
    if (!titulo) return alert("Escribe el nombre del juego");
    const btn = document.getElementById(idBoton);
    const original = btn.innerText;
    btn.innerText = "...";
    try {
        const res = await fetch('api/ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `title=${encodeURIComponent(titulo)}&action=${accion}`
        });
        const d = await res.json();
        if (d.error) alert(d.error);
        if (accion === 'get_year') document.getElementById('release_year').value = d.release_year;
        if (accion === 'get_desc') document.getElementById('description').value = d.description;
    } catch (e) { alert("Error de conexión"); }
    btn.innerText = original;
}
document.getElementById('btn-year').onclick = () => ejecutarIA('get_year', 'btn-year');
document.getElementById('btn-desc').onclick = () => ejecutarIA('get_desc', 'btn-desc');
</script>