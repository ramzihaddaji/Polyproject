<?php
// =====================================================
// CinéTrack - Fiche Détail d'un Film
// =====================================================
$pageTitle = htmlspecialchars($film['titre']);
include __DIR__ . '/../layouts/header.php';

// Calcul note moyenne étoiles
$noteMoyenne = round($film['note_moyenne'], 1);
$etoilesFull = floor($noteMoyenne);
?>

<!-- ============ FILM HERO ============ -->
<section class="film-hero">

    <!-- Fond flou -->
    <div class="film-hero__bg" style="background-image: url('<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>')"></div>
    <div class="film-hero__bg-overlay"></div>

    <div class="container film-hero__inner">

        <!-- Poster -->
        <div class="film-hero__poster-wrap">
            <img
                class="film-hero__poster"
                src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>"
                alt="<?= htmlspecialchars($film['titre']) ?>"
                onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'"
            >
        </div>

        <!-- Informations -->
        <div class="film-hero__info">

            <!-- Titre + Meta -->
            <h1 class="film-hero__title"><?= htmlspecialchars($film['titre']) ?></h1>

            <div class="film-hero__meta">
                <span class="badge badge-year"><?= $film['annee'] ?></span>
                <span class="badge badge-genre"><?= htmlspecialchars($film['genre']) ?></span>
                <span class="badge badge-director">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                    <?= htmlspecialchars($film['realisateur']) ?>
                </span>
            </div>

            <!-- Note moyenne -->
            <div class="film-hero__rating">
                <div class="stars-row">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star star--lg <?= $i <= $etoilesFull ? 'star--filled' : '' ?>">★</span>
                    <?php endfor; ?>
                </div>
                <span class="rating-value"><?= $noteMoyenne ?>/5</span>
                <span class="rating-count"><?= $film['nb_avis'] ?> avis</span>
            </div>

            <!-- Synopsis -->
            <p class="film-hero__synopsis"><?= nl2br(htmlspecialchars($film['synopsis'])) ?></p>

            <!-- Actions -->
            <div class="film-hero__actions">

                <?php if (isset($_SESSION['user_id'])): ?>

                    <!-- Bouton Watchlist -->
                    <?php if ($watchlistInfo): ?>
                        <div class="watchlist-actions">
                            <span class="btn btn-success btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                                Dans ma Watchlist
                            </span>
                            <?php if ($watchlistInfo['statut'] === 'a_voir'): ?>
                                <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=updateStatut&film_id=<?= $film['id'] ?>&statut=vu" class="btn btn-outline btn-sm">
                                    Marquer comme Vu
                                </a>
                            <?php else: ?>
                                <span class="badge badge-vu">✅ Film vu</span>
                                <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=updateStatut&film_id=<?= $film['id'] ?>&statut=a_voir" class="btn btn-outline btn-sm">
                                    Remettre en À voir
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=remove&film_id=<?= $film['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Retirer ce film de la watchlist ?')">
                                Retirer
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=add&film_id=<?= $film['id'] ?>" class="btn btn-gold btn-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Ajouter à ma Watchlist
                        </a>
                    <?php endif; ?>

                <?php else: ?>
                    <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="btn btn-gold btn-lg">
                        Connectez-vous pour ajouter
                    </a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="btn btn-outline btn-lg">
                    ← Retour au catalogue
                </a>

            </div>
        </div>
    </div>
</section>

<!-- ============ SECTION AVIS ============ -->
<section class="section">
    <div class="container">

        <div class="section__header">
            <h2 class="section__title">Avis & Commentaires</h2>
            <span class="section__subtitle"><?= count($avis) ?> avis pour ce film</span>
        </div>

        <!-- ---- Formulaire pour laisser un avis ---- -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form-card">
                <h3 class="review-form-card__title">
                    <?= $userAvis ? 'Modifier mon avis' : 'Laisser un avis' ?>
                </h3>
                <form action="<?= BASE_URL ?>/controllers/AvisController.php?action=submit" method="POST" class="review-form">
                    <input type="hidden" name="film_id" value="<?= $film['id'] ?>">

                    <!-- Note étoiles -->
                    <div class="form-group">
                        <label class="form-label">Votre note</label>
                        <div class="star-picker" id="star-picker">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="note" id="star<?= $i ?>" value="<?= $i ?>"
                                    <?= ($userAvis && $userAvis['note'] == $i) ? 'checked' : '' ?>>
                                <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="form-group">
                        <label class="form-label" for="commentaire">Votre commentaire (optionnel)</label>
                        <textarea
                            id="commentaire"
                            name="commentaire"
                            class="form-textarea"
                            rows="4"
                            placeholder="Partagez votre avis sur ce film..."
                        ><?= $userAvis ? htmlspecialchars($userAvis['commentaire']) : '' ?></textarea>
                    </div>

                    <div class="review-form__actions">
                        <button type="submit" class="btn btn-gold">
                            <?= $userAvis ? 'Mettre à jour' : 'Publier mon avis' ?>
                        </button>
                        <?php if ($userAvis): ?>
                            <a href="<?= BASE_URL ?>/controllers/AvisController.php?action=delete&id=<?= $userAvis['id'] ?>&film_id=<?= $film['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer votre avis ?')">
                                Supprimer mon avis
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <p>Connectez-vous pour noter et commenter ce film.</p>
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="btn btn-gold">Se connecter</a>
            </div>
        <?php endif; ?>

        <!-- ---- Liste des avis ---- -->
        <div class="reviews-list">
            <?php if (empty($avis)): ?>
                <div class="empty-state">
                    <div class="empty-state__icon">💬</div>
                    <h3>Aucun avis pour l'instant</h3>
                    <p>Soyez le premier à donner votre avis !</p>
                </div>
            <?php else: ?>
                <?php foreach ($avis as $av): ?>
                    <div class="review-card">
                        <div class="review-card__header">
                            <div class="review-card__author">
                                <div class="review-card__avatar">
                                    <?= strtoupper(mb_substr($av['auteur'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="review-card__name"><?= htmlspecialchars($av['auteur']) ?></div>
                                    <div class="review-card__date"><?= date('d/m/Y à H:i', strtotime($av['date_avis'])) ?></div>
                                </div>
                            </div>
                            <div class="stars-row">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= $av['note'] ? 'star--filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if (!empty($av['commentaire'])): ?>
                            <p class="review-card__text"><?= nl2br(htmlspecialchars($av['commentaire'])) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>