<!-- Contenedor del Título -->
<div class="input-group">
    <label class="neon-label"><?= h(t('title')) ?></label>
    <input type="text" name="title" value="<?= h($game['title'] ?? '') ?>" required placeholder="Nombre del juego..." class="gamer-input">
</div>

<!-- Año de Lanzamiento con Botón IA -->
<div class="input-group">
    <label class="neon-label"><?= h(t('release_year')) ?></label>
    <div class="ai-input-wrapper">
        <input type="number" name="release_year" min="1950" max="2100" value="<?= h((string)($game['release_year'] ?? '')) ?>" class="gamer-input">
        <button type="button" class="btn-ai-mini" onclick="alert('<?= h(t('ai_pending')) ?>')">✨ IA</button>
    </div>
</div>

<!-- Imagen y Extracción por IA -->
<div class="input-group">
    <label class="neon-label"><?= h(t('image')) ?></label>
    <div class="image-upload-zone">
        <input type="file" name="image" accept="image/*" class="gamer-input-file">
        <button type="button" class="btn-ai-text" onclick="alert('<?= h(t('ai_pending')) ?>')">
            🔍 <?= h(t('ai_image')) ?>
        </button>
    </div>
    <?php if (!empty($game['image_path'])): ?>
        <div class="current-image-preview">
            <p>Imagen actual:</p>
            <img src="<?= h($game['image_path']) ?>" alt="Portada" class="img-preview">
        </div>
    <?php endif; ?>
</div>

<!-- Estado del Juego -->
<div class="input-group">
    <label class="neon-label"><?= h(t('status')) ?></label>
    <select name="status" class="gamer-select">
        <?php foreach (['Sin Iniciar', 'En progreso', 'Completado', 'Abandonado'] as $status): ?>
            <option value="<?= h($status) ?>" <?= (($game['status'] ?? '') === $status) ? 'selected' : '' ?>>
                <?= h($status) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Progreso con Slider Dinámico -->
<div class="input-group">
    <label class="neon-label"><?= h(t('progress')) ?> (%)</label>
    <div class="range-wrapper">
        <input type="range" name="progress" min="0" max="100" value="<?= h((string)($game['progress'] ?? 0)) ?>" 
               class="gamer-range" oninput="this.nextElementSibling.value = this.value + '%'">
        <output class="range-output"><?= h((string)($game['progress'] ?? 0)) ?>%</output>
    </div>
</div>

<!-- Calificación por Estrellas -->
<div class="input-group">
    <label class="neon-label"><?= h(t('rating')) ?></label>
    <select name="rating" class="gamer-select star-select">
        <option value=""><?= h(t('no_rating') ?? 'Sin calificación') ?></option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>" <?= ((string)($game['rating'] ?? '') === (string)$i) ? 'selected' : '' ?>>
                <?= $i ?> ★<?= $i > 1 ? 's' : '' ?>
            </option>
        <?php endfor; ?>
    </select>
</div>

<!-- Descripción con Autocompletado IA -->
<div class="input-group">
    <label class="neon-label"><?= h(t('description')) ?></label>
    <textarea name="description" rows="3" class="gamer-input"><?= h($game['description'] ?? '') ?></textarea>
    <button type="button" class="btn-ai-text" onclick="alert('<?= h(t('ai_pending')) ?>')">
         <?= h(t('ai_description')) ?>
    </button>
</div>

<!-- Notas -->
<div class="input-group">
    <label class="neon-label"><?= h(t('notes')) ?></label>
    <textarea name="notes" rows="2" class="gamer-input"><?= h($game['notes'] ?? '') ?></textarea>
</div>

<!-- Acciones Finales -->
<div class="form-footer">
    <button type="submit" class="btn-neon-save"><?= h(t('save')) ?></button>
    <a href="index.php" class="btn-cancel"><?= h(t('cancel')) ?></a>
</div>