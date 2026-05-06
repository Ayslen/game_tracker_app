<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
require_login();
require_once __DIR__ . '/inc/header.php';
?>

<main class="dashboard-main">
    <header class="welcome-header">
        <h1 class="neon-text">¡Let's Play!</h1>
        <h2 class="neon-text-blue">BIENVENIDO, <?= h($_SESSION['username'] ?? 'PLAYER') ?></h2>
    </header>

    <section class="controls-container">
        <div class="search-box">
            <label class="neon-label"><?= h(t('search')) ?></label>
            <input type="search" id="search" placeholder="🔍 Buscar título..." class="gamer-input">
        </div>

        <div class="filter-group">
            <div>
                <label class="neon-label"><?= h(t('sort_by')) ?></label>
                <select id="sort" class="gamer-select">
                    <option value="updated_at"><?= h(t('updated_at')) ?></option>
                    <option value="created_at"><?= h(t('created_at')) ?></option>
                    <option value="title"><?= h(t('alphabetical')) ?></option>
                </select>
            </div>

            <div>
                <label class="neon-label">Orden</label>
                <select id="order" class="gamer-select">
                    <option value="desc"><?= h(t('descending')) ?></option>
                    <option value="asc"><?= h(t('ascending')) ?></option>
                </select>
            </div>

            <button type="button" onclick="resetAndLoad()" class="btn-apply">APLICAR FILTROS</button>
        </div>
        
        <a href="add_game.php" class="btn-neon-add">+ <?= h(t('add_game')) ?></a>
    </section>

    <hr class="neon-divider">

    <section id="game-list" class="game-cards-grid"></section>

    <div class="load-more-container">
        <button id="load-more" type="button" onclick="loadGames()" class="btn-load">CARGAR MÁS</button>
    </div>
</main>

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

// Genera el diseño de tarjetas Gamer - CORREGIDO POR ALUMNO 4
function gameHtml(game) {
    const image = game.image_path
        ? `<div class="game-poster"><img src="${escapeHtml(game.image_path)}" alt="Portada"></div>`
        : `<div class="game-poster no-image"><span>NO IMAGE</span></div>`;

    const year = game.release_year ? `<span class="game-year">${escapeHtml(game.release_year)}</span>` : '';
    
    // CORRECCIÓN: Usamos game.rating (que es el nombre en la DB) en lugar de game.stars
    const currentRating = parseInt(game.rating || 0);
    let starsHtml = '';
    for(let i=1; i<=5; i++) {
        starsHtml += `<span class="star ${i <= currentRating ? 'filled' : ''}">★</span>`;
    }

    // Clase de estado para colores neón
    const statusClass = `status-${escapeHtml(game.status).toLowerCase().replace(/\s+/g, '-')}`;

    return `
        <article class="game-card">
            ${image}
            <div class="game-card-content">
                <div class="game-card-header">
                    <h3>${escapeHtml(game.title)} ${year}</h3>
                    <div class="star-rating">${starsHtml}</div>
                </div>
                
                <span class="status-badge ${statusClass}">${escapeHtml(game.status)}</span>

                <div class="progress-section">
                    <div class="progress-info">
                        <span>Progreso</span>
                        <span>${escapeHtml(game.progress)}%</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: ${escapeHtml(game.progress)}%"></div>
                    </div>
                </div>

                <p class="game-description">${escapeHtml(game.description || 'Sin descripción disponible...')}</p>

                <div class="game-actions">
                    <a href="edit_game.php?id=${escapeHtml(game.id)}" class="btn-card-edit">EDITAR</a>
                    <form method="post" action="delete_game.php" onsubmit="return confirm('¿Eliminar esta partida permanentemente?')" style="display:inline">
                        <input type="hidden" name="id" value="${escapeHtml(game.id)}">
                        <button type="submit" class="btn-card-delete">BORRAR</button>
                    </form>
                </div>
            </div>
        </article>
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

    try {
        const response = await fetch(`api/games.php?${params.toString()}`);
        const data = await response.json();

        if (data.games && data.games.length > 0) {
            data.games.forEach(game => {
                list.insertAdjacentHTML('beforeend', gameHtml(game));
            });
        } else if (page === 1) {
            list.innerHTML = '<div class="no-results">No se encontraron juegos en tu biblioteca.</div>';
        }

        hasMore = Boolean(data.has_more);
        page++;
    } catch (error) {
        console.error("Error cargando juegos:", error);
    } finally {
        loadMoreButton.disabled = false;
        loadMoreButton.style.display = hasMore ? 'inline-block' : 'none';
        loading = false;
    }
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

// Implementación de Scroll Infinito
window.addEventListener('scroll', () => {
    const nearBottom = window.innerHeight + window.scrollY >= document.body.offsetHeight - 400;
    if (nearBottom) {
        loadGames();
    }
});

loadGames();
</script>

<?php require_once __DIR__ . '/inc/footer.php'; ?>