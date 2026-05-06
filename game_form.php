<label><?= h(t('title')) ?></label><br>
<input type="text" name="title" value="<?= h($game['title'] ?? '') ?>" required><br><br>

<label><?= h(t('description')) ?></label><br>
<textarea name="description" rows="5" cols="50"><?= h($game['description'] ?? '') ?></textarea><br>
<button type="button" onclick="alert('<?= h(t('ai_pending')) ?>')"><?= h(t('ai_description')) ?></button><br><br>

<label><?= h(t('notes')) ?></label><br>
<textarea name="notes" rows="5" cols="50"><?= h($game['notes'] ?? '') ?></textarea><br><br>

<label><?= h(t('image')) ?></label><br>
<input type="file" name="image" accept="image/*"><br>
<button type="button" onclick="alert('<?= h(t('ai_pending')) ?>')"><?= h(t('ai_image')) ?></button><br>
<?php if (!empty($game['image_path'])): ?>
    <p>Imagen actual:</p>
    <img src="<?= h($game['image_path']) ?>" alt="Portada" width="120">
<?php endif; ?>
<br><br>

<label><?= h(t('status')) ?></label><br>
<select name="status">
    <?php foreach (['Sin Iniciar', 'En progreso', 'Completado', 'Abandonado'] as $status): ?>
        <option value="<?= h($status) ?>" <?= (($game['status'] ?? '') === $status) ? 'selected' : '' ?>><?= h($status) ?></option>
    <?php endforeach; ?>
</select><br><br>

<label><?= h(t('progress')) ?> (%)</label><br>
<input type="number" name="progress" min="0" max="100" value="<?= h((string)($game['progress'] ?? 0)) ?>"><br><br>

<label><?= h(t('rating')) ?></label><br>
<select name="rating">
    <option value="">Sin calificación</option>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <option value="<?= $i ?>" <?= ((string)($game['rating'] ?? '') === (string)$i) ? 'selected' : '' ?>><?= $i ?> estrella<?= $i > 1 ? 's' : '' ?></option>
    <?php endfor; ?>
</select><br><br>

<label><?= h(t('release_year')) ?></label><br>
<input type="number" name="release_year" min="1950" max="2100" value="<?= h((string)($game['release_year'] ?? '')) ?>"><br>
<button type="button" onclick="alert('<?= h(t('ai_pending')) ?>')"><?= h(t('ai_year')) ?></button><br><br>

<button type="submit"><?= h(t('save')) ?></button>
<a href="index.php"><?= h(t('cancel')) ?></a>
