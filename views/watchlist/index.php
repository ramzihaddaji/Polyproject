<?php
// =====================================================
// CinéTrack - Ma Watchlist
// =====================================================
$pageTitle = 'Ma Watchlist';
include __DIR__ . '/../layouts/header.php';

$statut = $_GET['statut'] ?? '';
$aVoir  = array_filter($films, fn($f) => $f['statut'] === 'a_voir');
$vus    = array_filter($films, fn($f) => $f['statut'] === 'vu');
?>

<section class="section">
    <div class="container">

        <!-- En-tête -->
        <div class="section__header">
            <div>
                <h1 class="section__title">Ma Watchlist</h1>
                <p class="section__subtitle">
                    <?= count($films) ?> film<?= count($films) > 1 ? 's' : '' ?> •
                    <?= count($aVoir) ?> à voir •
                    <?= count($vus) ?> vu<?= count($vus) > 1 ? 's' : '' ?>
                </p>
            </div>
            <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="btn btn-gold">
                + Ajouter des films
            </a>
        </div>

        <!-- Onglets filtres -->
        <div class="tabs" role="tablist">
            <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index"
               class="tab <?= $statut === '' ? 'tab--active' : '' ?>"
               role="tab">
                Tous (<?= count($films) ?>)
            </a>
            <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index&statut=a_voir"
               class="tab <?= $statut === 'a_voir' ? 'tab--active' : '' ?>"
               role="tab">
                🕐 À voir (<?= count($aVoir) ?>)
            </a>
            <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index&statut=vu"
               class="tab <?= $statut === 'vu' ? 'tab--active' : '' ?>"
               role="tab">
                ✅ Vus (<?= count($vus) ?>)
            </a>
        </div>

        <!-- Liste films watchlist -->
        <?php if (empty($films)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">📋</div>
                <h3>Votre watchlist est vide</h3>
                <p>Parcourez le catalogue et ajoutez des films à follow !</p>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="btn btn-gold">
                    Découvrir des films
                </a>
            </div>
        <?php else: ?>
            <div class="watchlist-grid">
                <?php foreach ($films as $film): ?>
                    <div class="watchlist-card <?= $film['statut'] === 'vu' ? 'watchlist-card--vu' : '' ?>">

                        <!-- Poster -->
                        <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=detail&id=<?= $film['film_id'] ?>" class="watchlist-card__poster-wrap">
                            <img
                                src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>"
                                alt="<?= htmlspecialchars($film['titre']) ?>"
                                onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'"
                                loading="lazy"
                            >
                            <?php if ($film['statut'] === 'vu'): ?>
                                <div class="watchlist-card__vu-badge">✅ VU</div>
                            <?php endif; ?>
                        </a>

                        <!-- Infos -->
                        <div class="watchlist-card__body">
                            <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=detail&id=<?= $film['film_id'] ?>" class="watchlist-card__title">
                                <?= htmlspecialchars($film['titre']) ?>
                            </a>
                            <div class="watchlist-card__meta">
                                <span><?= $film['annee'] ?></span>
                                <span class="badge badge-genre"><?= htmlspecialchars($film['genre']) ?></span>
                            </div>

                            <!-- Note moyenne -->
                            <div class="stars-row stars-row--sm">
                                <?php $n = round($film['note_moyenne']); for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= $n ? 'star--filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>

                            <!-- Date ajout -->
                            <div class="watchlist-card__date">
                                Ajouté le <?= date('d/m/Y', strtotime($film['date_ajout'])) ?>
                            </div>

                            <!-- Actions -->
                            <div class="watchlist-card__actions">

                                <?php if ($film['statut'] === 'a_voir'): ?>
                                    <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=updateStatut&film_id=<?= $film['film_id'] ?>&statut=vu"
                                       class="btn btn-success btn-sm">
                                        ✅ Marquer comme vu
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=updateStatut&film_id=<?= $film['film_id'] ?>&statut=a_voir"
                                       class="btn btn-outline btn-sm">
                                        🕐 Remettre en À voir
                                    </a>
                                <?php endif; ?>

                                <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=remove&film_id=<?= $film['film_id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Retirer ce film ?')">
                                    Retirer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
