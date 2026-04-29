<?php
// =====================================================
// CinéTrack - Page Watchlist
// =====================================================
$pageTitle = 'Ma Watchlist';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">

    <!-- ===== EN-TÊTE WATCHLIST ===== -->
    <div class="page-header">
        <h1 class="page-title">📋 Ma <span class="text-gold">Watchlist</span></h1>
        <p class="page-subtitle">Gérez vos films à voir et ceux que vous avez déjà vus</p>
    </div>

    <!-- ===== STATISTIQUES ===== -->
    <div class="watchlist-stats">
        <div class="stat-card">
            <span class="stat-icon">🎬</span>
            <span class="stat-number"><?= $stats['total'] ?></span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-card stat-card-gold">
            <span class="stat-icon">🕐</span>
            <span class="stat-number"><?= $stats['a_voir'] ?></span>
            <span class="stat-label">À voir</span>
        </div>
        <div class="stat-card stat-card-green">
            <span class="stat-icon">✅</span>
            <span class="stat-number"><?= $stats['vu'] ?></span>
            <span class="stat-label">Vus</span>
        </div>
    </div>

    <!-- ===== FILTRES ONGLETS ===== -->
    <div class="watchlist-tabs">
        <a href="<?= BASE_URL ?>/index.php?action=watchlist"
           class="tab-btn <?= empty($_GET['statut']) ? 'active' : '' ?>">Tous</a>
        <a href="<?= BASE_URL ?>/index.php?action=watchlist&statut=a_voir"
           class="tab-btn <?= ($_GET['statut'] ?? '') === 'a_voir' ? 'active' : '' ?>">🕐 À voir</a>
        <a href="<?= BASE_URL ?>/index.php?action=watchlist&statut=vu"
           class="tab-btn <?= ($_GET['statut'] ?? '') === 'vu' ? 'active' : '' ?>">✅ Vus</a>
    </div>

    <!-- ===== LISTE DES FILMS ===== -->
    <?php if (empty($films)): ?>
        <div class="empty-state">
            <div class="empty-icon">🎬</div>
            <h3>Votre liste est vide</h3>
            <p>Explorez le catalogue et ajoutez des films à votre liste !</p>
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-gold">Parcourir le catalogue</a>
        </div>
    <?php else: ?>
        <div class="watchlist-grid">
            <?php foreach ($films as $film): ?>
                <div class="watchlist-card">
                    <a href="<?= BASE_URL ?>/index.php?action=detail&id=<?= $film['film_id'] ?>" class="watchlist-card-link">
                        <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>"
                             alt="<?= htmlspecialchars($film['titre']) ?>"
                             class="watchlist-img"
                             onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                        <div class="watchlist-info">
                            <h3><?= htmlspecialchars($film['titre']) ?></h3>
                            <div class="watchlist-meta">
                                <span class="film-genre"><?= htmlspecialchars($film['genre']) ?></span>
                                <span class="film-year">📅 <?= $film['annee'] ?></span>
                            </div>
                            <span class="watchlist-badge <?= $film['statut'] === 'vu' ? 'badge-vu' : 'badge-a-voir' ?>">
                                <?= $film['statut'] === 'vu' ? '✅ Vu' : '🕐 À voir' ?>
                            </span>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="watchlist-actions">
                        <!-- Changer statut -->
                        <form method="POST" action="<?= BASE_URL ?>/index.php?action=watchlist_update" class="status-form">
                            <input type="hidden" name="film_id" value="<?= $film['film_id'] ?>">
                            <select name="statut" class="status-select-sm" onchange="this.form.submit()">
                                <option value="a_voir" <?= $film['statut'] === 'a_voir' ? 'selected' : '' ?>>🕐 À voir</option>
                                <option value="vu" <?= $film['statut'] === 'vu' ? 'selected' : '' ?>>✅ Vu</option>
                            </select>
                        </form>

                        <!-- Note personnelle -->
                        <form method="POST" action="<?= BASE_URL ?>/index.php?action=watchlist_update" class="note-form">
                            <input type="hidden" name="film_id" value="<?= $film['film_id'] ?>">
                            <input type="hidden" name="statut" value="<?= $film['statut'] ?>">
                            <select name="note" class="status-select-sm" onchange="this.form.submit()" title="Ma note">
                                <option value="">⭐ Ma note</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $film['note'] == $i ? 'selected' : '' ?>>
                                        <?= str_repeat('★', $i) ?> <?= $i ?>/5
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </form>

                        <!-- Retirer -->
                        <form method="POST" action="<?= BASE_URL ?>/index.php?action=watchlist_remove"
                              onsubmit="return confirm('Retirer ce film ?')">
                            <input type="hidden" name="film_id" value="<?= $film['film_id'] ?>">
                            <button type="submit" class="btn-remove" title="Retirer">🗑️</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
