<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_login();
require_once __DIR__ . '/inc/header.php';
?>

<h2>Hola, <?= h($_SESSION['username'] ?? '') ?></h2>

<section>
    <label><?= h(t('search')) ?></label><br>
    <input type="search" id="search" placeholder="<?= h(t('search')) ?>"><br><br>

    <label><?= h(t('sort_by')) ?></label><br>
    <select id="sort">
        <option value="updated_at"><?= h(t('updated_at')) ?></option>
        <option value="created_at"><?= h(t('created_at')) ?></option>
        <option value="title"><?= h(t('alphabetical')) ?></option>
    </select>

    <select id="order">
        <option value="desc"><?= h(t('descending')) ?></option>
        <option value="asc"><?= h(t('ascending')) ?></option>
    </select>

    <button type="button" onclick="resetAndLoad()">Aplicar</button>
    <a href="add_game.php">+ <?= h(t('add_game')) ?></a>
</section>

<hr>

<section id="game-list"></section>

<button id="load-more" type="button" onclick="loadGames()"><?= h(t('load_more')) ?></button>

<p><a href="add_game.php">+ <?= h(t('add_game')) ?></a></p>

<script>
let page = 1;
let loading = false;
let hasMore = true;

const list = document.getElementById('game-list');
const loadMoreButton = document.getElementById('load-more');
const searchInput = document.getElementById('search');
const sortInput = document.getElementById('sort');
const orderInput = document.getElementById('order');

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function gameHtml(game) {
    const image = game.image_path
        ? `<img src="${escapeHtml(game.image_path)}" alt="Portada" width="120">`
        : `<p>Sin imagen</p>`;

    const year = game.release_year ? ` (${escapeHtml(game.release_year)})` : '';

    return `
        <article>
            ${image}
            <h3>${escapeHtml(game.title)}${year}</h3>
            <p><strong>Estado:</strong> ${escapeHtml(game.status)}</p>
            <p><strong>Progreso:</strong> ${escapeHtml(game.progress)}%</p>
            <progress value="${escapeHtml(game.progress)}" max="100"></progress>
            <p><strong>Calificación:</strong> ${escapeHtml(game.stars)}</p>
            <p><strong>Descripción:</strong><br>${escapeHtml(game.description || '')}</p>
            <p><strong>Notas:</strong><br>${escapeHtml(game.notes || '')}</p>
            <p>
                <a href="edit_game.php?id=${escapeHtml(game.id)}">Editar</a>
                <form method="post" action="delete_game.php" onsubmit="return confirm('¿Seguro que deseas borrar este juego?')" style="display:inline">
                    <input type="hidden" name="id" value="${escapeHtml(game.id)}">
                    <button type="submit">Borrar</button>
                </form>
            </p>
        </article>
        <hr>
    `;
}

async function loadGames() {
    if (loading || !hasMore) return;

    loading = true;
    loadMoreButton.disabled = true;

    const params = new URLSearchParams({
        page,
        q: searchInput.value,
        sort: sortInput.value,
        order: orderInput.value
    });

    const response = await fetch(`api/games.php?${params.toString()}`);
    const data = await response.json();

    if (data.games && data.games.length > 0) {
        data.games.forEach(game => {
            list.insertAdjacentHTML('beforeend', gameHtml(game));
        });
    } else if (page === 1) {
        list.innerHTML = '<p>No hay juegos en la lista.</p>';
    }

    hasMore = Boolean(data.has_more);
    page++;
    loadMoreButton.disabled = false;
    loadMoreButton.style.display = hasMore ? 'inline-block' : 'none';
    loading = false;
}

function resetAndLoad() {
    page = 1;
    hasMore = true;
    list.innerHTML = '';
    loadMoreButton.style.display = 'inline-block';
    loadGames();
}

let searchTimer = null;
searchInput.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(resetAndLoad, 300);
});

window.addEventListener('scroll', () => {
    const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 250;
    if (nearBottom) {
        loadGames();
    }
});

loadGames();
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
