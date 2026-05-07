<?php
$current_rating = isset($game['rating']) ? (int)$game['rating'] : 0;
$current_status = $game['status'] ?? 'Sin Iniciar';
$current_progress = isset($game['progress']) ? (int)$game['progress'] : 0;
$current_notes = $game['notes'] ?? '';
$current_image = $game['image_path'] ?? '';
?>

<style>
    :root {
        --neon-cyan: #00f2ff;
        --neon-purple: #bc13fe;
        --dark-bg: #0d0d0d;
        --card-bg: #1a1a1a;
    }

    .gamer-container {
        font-family: 'Segoe UI', Roboto, sans-serif;
        color: #fff;
    }

    .gamer-label {
        color: var(--neon-cyan);
        font-weight: 800;
        display: block;
        margin-bottom: 8px;
        text-transform: uppercase;
        font-size: 0.85em;
        letter-spacing: 1.5px;
        text-shadow: 0 0 5px rgba(0, 242, 255, 0.3);
    }

    .gamer-field {
        width: 100%;
        padding: 14px;
        background: rgba(255, 255, 255, 0.05);
        color: white;
        border: 1px solid #333;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 1em;
        box-sizing: border-box;
    }

    .gamer-field:focus {
        border-color: var(--neon-cyan);
        background: rgba(0, 242, 255, 0.05);
        outline: none;
        box-shadow: 0 0 15px rgba(0, 242, 255, 0.2);
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 10px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 35px;
        color: #333;
        cursor: pointer;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .star-rating label:hover {
        transform: scale(1.2);
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #ffea00;
        text-shadow: 0 0 15px rgba(255, 234, 0, 0.7);
    }

    .btn-ia-small {
        background: var(--neon-cyan);
        color: #000;
        border: none;
        padding: 0 15px;
        cursor: pointer;
        font-weight: 900;
        border-radius: 6px;
        text-transform: uppercase;
        font-size: 0.75em;
        transition: all 0.3s ease;
    }

    .btn-ia-small:hover {
        background: #fff;
        box-shadow: 0 0 15px var(--neon-cyan);
    }

    .cover-zone {
        margin-top: 30px;
        padding: 25px;
        background: rgba(0, 242, 255, 0.03);
        border: 2px dashed #333;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .cover-zone:hover {
        border-color: var(--neon-cyan);
        background: rgba(0, 242, 255, 0.07);
    }

    #cover_preview {
        width: 180px;
        height: 240px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 20px;
        display: <?= $current_image ? 'inline-block' : 'none' ?>;
        border: 3px solid #222;
        box-shadow: 0 10px 30px rgba(0,0,0,0.8);
    }

    .btn-upload {
        display: inline-block;
        background: transparent;
        color: #fff;
        border: 2px solid var(--neon-cyan);
        padding: 12px 25px;
        border-radius: 50px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-upload:hover {
        background: var(--neon-cyan);
        color: #000;
    }

    .progress-value {
        color: var(--neon-cyan);
        font-weight: bold;
        margin-top: 8px;
    }
</style>

<div class="gamer-container">
    <div class="gamer-input-group" style="margin-bottom: 25px;">
        <label class="gamer-label">Identificación del Juego</label>
        <input
            type="text"
            id="game_title"
            name="title"
            value="<?= h($game['title'] ?? '') ?>"
            class="gamer-field"
            required
            placeholder="Nombre del videojuego..."
        >
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div style="flex: 1;">
            <label class="gamer-label">Lanzamiento</label>
            <div style="display: flex; gap: 8px; height: 50px;">
                <input
                    type="number"
                    id="release_year"
                    name="release_year"
                    value="<?= h((string)($game['release_year'] ?? '')) ?>"
                    class="gamer-field"
                    placeholder="YYYY"
                >
                <button type="button" id="btn-year" class="btn-ia-small">IA</button>
            </div>
        </div>

        <div style="flex: 1;">
            <label class="gamer-label">Nivel de Calidad</label>
            <div class="star-rating">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input
                        type="radio"
                        id="star<?= $i ?>"
                        name="rating"
                        value="<?= $i ?>"
                        <?= ($current_rating === $i) ? 'checked' : '' ?>
                    >
                    <label for="star<?= $i ?>">★</label>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <div class="gamer-input-group" style="margin-bottom: 25px;">
        <label class="gamer-label">Estado del juego</label>
        <select name="status" class="gamer-field">
            <?php
            $statuses = ['Sin Iniciar', 'En progreso', 'Completado', 'Abandonado'];
            foreach ($statuses as $statusOption):
            ?>
                <option value="<?= h($statusOption) ?>" <?= ($current_status === $statusOption) ? 'selected' : '' ?>>
                    <?= h($statusOption) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="gamer-input-group" style="margin-bottom: 25px;">
        <label class="gamer-label">Progreso</label>
        <input
            type="range"
            id="progress"
            name="progress"
            min="0"
            max="100"
            value="<?= h((string)$current_progress) ?>"
            class="gamer-field"
            oninput="document.getElementById('progress_value').textContent = this.value + '%'"
        >
        <div id="progress_value" class="progress-value"><?= h((string)$current_progress) ?>%</div>
    </div>

    <div class="gamer-input-group" style="margin-bottom: 25px;">
        <label class="gamer-label">Análisis de Datos (Sinopsis)</label>
        <div style="position: relative;">
            <textarea
                id="description"
                name="description"
                rows="5"
                class="gamer-field"
                style="resize: none;"
                placeholder="Descripción del videojuego..."
            ><?= h($game['description'] ?? '') ?></textarea>
            <button type="button" id="btn-desc" class="btn-ia-small" style="position: absolute; right: 15px; bottom: 15px; height: 35px;">
                Sincronizar IA
            </button>
        </div>
    </div>

    <div class="gamer-input-group" style="margin-bottom: 25px;">
        <label class="gamer-label">Notas personales</label>
        <textarea
            name="notes"
            rows="4"
            class="gamer-field"
            style="resize: vertical;"
            placeholder="Escribe notas personales sobre este juego..."
        ><?= h($current_notes) ?></textarea>
    </div>

    <div class="cover-zone">
        <label class="gamer-label" style="margin-bottom: 15px;">Agregar carátula</label>

        <div id="preview_container">
            <img id="cover_preview" src="<?= $current_image ? h($current_image) : '#' ?>" alt="Portada">
        </div>

        <input type="file" name="image" id="image_input" accept="image/jpeg,image/png,image/webp,image/gif,image/avif,.jfif" style="display: none;">

        <button type="button" class="btn-upload" onclick="document.getElementById('image_input').click()">
            <span style="margin-right: 10px;">💾</span> SELECCIONAR IMAGEN
        </button>

        <p style="font-size: 0.7em; color: #555; margin-top: 10px;">
            Formatos aceptados: JPG, JPEG, JFIF, PNG, WEBP, GIF, AVIF. Máximo 5 MB.
        </p>
    </div>
</div>

<script>
const imageInput = document.getElementById('image_input');

if (imageInput) {
    imageInput.onchange = function() {
        const [file] = this.files;

        if (file) {
            const preview = document.getElementById('cover_preview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'inline-block';
            preview.style.border = '3px solid var(--neon-cyan)';
        }
    };
}

async function ejecutarIA(accion, idBoton) {
    const elTitulo = document.getElementById('game_title');
    const elYear = document.getElementById('release_year');
    const elDesc = document.getElementById('description');

    if (!elTitulo || !elTitulo.value.trim()) {
        alert("Escribe el nombre del juego primero.");
        return;
    }

    const btn = document.getElementById(idBoton);
    const originalText = btn.innerText;

    btn.innerText = "Cargando...";
    btn.disabled = true;
    btn.style.opacity = "0.5";

    try {
        const response = await fetch('api/ai.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `title=${encodeURIComponent(elTitulo.value)}&action=${accion}`
        });

        const result = await response.json();

        if (result.status === 'success') {
            if (accion === 'get_year') {
                elYear.value = String(result.data).replace(/\D/g, '').slice(0, 4);
                elYear.style.borderColor = 'var(--neon-cyan)';
            } else if (accion === 'get_desc') {
                elDesc.value = result.data;
                elDesc.style.borderColor = 'var(--neon-cyan)';
            }
        } else {
            alert(result.message || "No se pudo obtener respuesta de la IA.");
        }
    } catch (e) {
        alert("No se pudo conectar con la API de IA.");
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
        btn.style.opacity = "1";
    }
}

document.getElementById('btn-year').onclick = () => ejecutarIA('get_year', 'btn-year');
document.getElementById('btn-desc').onclick = () => ejecutarIA('get_desc', 'btn-desc');
</script>