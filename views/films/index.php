<?php
// =====================================================
// CinéTrack - Catalogue des films
// =====================================================
$pageTitle = 'Catalogue';
include __DIR__ . '/../layouts/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero">
    <div class="hero__content">
        <div class="hero__badge">🎬 Bienvenue sur CinéTrack</div>
        <h1 class="hero__title">
            Découvrez, Notez<br>
            <span class="hero__title-accent">& Suivez vos Films</span>
        </h1>
        <p class="hero__subtitle">
            Le catalogue ultime pour organiser votre vie cinématographique. Ajoutez des films à votre watchlist, partagez vos avis et explorez des milliers de titres.
        </p>
        <div class="hero__actions">
            <a href="#catalogue" class="btn btn-gold btn-lg">Explorer le catalogue</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register" class="btn btn-outline btn-lg">Créer un compte</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index" class="btn btn-outline btn-lg">Ma Watchlist</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero__overlay"></div>
</section>

<!-- ============ CATALOGUE ============ -->
<section class="section" id="catalogue">
    <div class="container">

        <!-- En-tête section -->
        <div class="section__header">
            <div>
                <h2 class="section__title">Catalogue des Films</h2>
                <p class="section__subtitle"><?= count($films) ?> film<?= count($films) > 1 ? 's' : '' ?> disponible<?= count($films) > 1 ? 's' : '' ?></p>
            </div>
        </div>

        <!-- ---- Barre de filtres ---- -->
        <form class="filters-bar" id="filters-form" method="GET" action="<?= BASE_URL ?>/controllers/FilmController.php">
            <input type="hidden" name="action" value="index">

            <!-- Recherche texte -->
            <div class="filter-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input
                    type="text"
                    name="search"
                    id="search-input"
                    class="filter-search__input"
                    placeholder="Titre, réalisateur..."
                    value="<?= htmlspecialchars($search) ?>"
                >
            </div>

            <!-- Filtre genre -->
            <div class="filter-group">
                <label class="filter-label">Genre</label>
                <select name="genre" class="form-select" id="filter-genre" onchange="this.form.submit()">
                    <option value="">Tous les genres</option>
                    <?php foreach ($genres as $g): ?>
                        <option value="<?= htmlspecialchars($g) ?>" <?= $genre === $g ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre année -->
            <div class="filter-group">
                <label class="filter-label">Année</label>
                <select name="annee" class="form-select" id="filter-annee" onchange="this.form.submit()">
                    <option value="">Toutes les années</option>
                    <?php foreach ($annees as $a): ?>
                        <option value="<?= $a ?>" <?= $annee == $a ? 'selected' : '' ?>>
                            <?= $a ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton reset -->
            <?php if ($genre || $annee || $search): ?>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="btn btn-outline btn-sm">
                    Réinitialiser
                </a>
            <?php endif; ?>

            <button type="submit" class="btn btn-gold btn-sm">Filtrer</button>
        </form>

        <!-- ---- Grille des films ---- -->
        <div class="grid-films" id="films-grid">
            <?php if (empty($films)): ?>
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-state__icon">🎥</div>
                    <h3>Aucun film trouvé</h3>
                    <p>Essayez de modifier vos filtres de recherche.</p>
                    <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="btn btn-gold">Voir tous les films</a>
                </div>
            <?php else: ?>
                <?php foreach ($films as $film): ?>
                    <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=detail&id=<?= $film['id'] ?>" class="film-card" data-title="<?= strtolower(htmlspecialchars($film['titre'])) ?>">

                        <!-- Poster -->
                        <div class="film-card__poster">
                            <?php
                            $imgSrc = BASE_URL . '/assets/img/' . ($film['image'] ?? 'default.jpg');
                            // Poster de secours coloré si l'image est default
                            ?>
                            <img
                                src="<?= $imgSrc ?>"
                                alt="<?= htmlspecialchars($film['titre']) ?>"
                                onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'; this.onerror=null;"
                                loading="lazy"
                            >
                            <div class="film-card__overlay">
                                <span class="btn btn-gold btn-sm">Voir le film</span>
                            </div>
                            <div class="film-card__badge-genre"><?= htmlspecialchars($film['genre']) ?></div>
                        </div>

                        <!-- Infos -->
                        <div class="film-card__body">
                            <div class="film-card__title"><?= htmlspecialchars($film['titre']) ?></div>
                            <div class="film-card__meta">
                                <span class="film-card__year"><?= $film['annee'] ?></span>
                                <span class="film-card__director">🎬 <?= htmlspecialchars($film['realisateur']) ?></span>
                            </div>
                            <div class="film-card__rating">
                                <?php
                                $note = round($film['note_moyenne']);
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <span class="star <?= $i <= $note ? 'star--filled' : '' ?>">★</span>
                                <?php endfor; ?>
                                <span class="film-card__nb-avis">(<?= $film['nb_avis'] ?> avis)</span>
                            </div>
                        </div>

                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>