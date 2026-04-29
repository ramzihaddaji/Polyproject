<?php
// =====================================================
// CinéTrack - Admin Dashboard
// =====================================================
$pageTitle = 'Administration';
include __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container">

        <!-- En-tête -->
        <div class="section__header">
            <div>
                <h1 class="section__title">Administration</h1>
                <p class="section__subtitle">Gérez le catalogue de films</p>
            </div>
            <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=create" class="btn btn-gold">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Ajouter un film
            </a>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card__icon">🎬</div>
                <div class="stat-card__value"><?= $nb_films ?></div>
                <div class="stat-card__label">Films dans le catalogue</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon">👥</div>
                <div class="stat-card__value"><?= $nb_users ?></div>
                <div class="stat-card__label">Utilisateurs inscrits</div>
            </div>
            <div class="stat-card">
                <div class="stat-card__icon">⭐</div>
                <div class="stat-card__value">—</div>
                <div class="stat-card__label">Avis publiés</div>
            </div>
        </div>

        <!-- Message de confirmation -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Barre recherche admin -->
        <div class="filters-bar" style="margin-bottom: 1.5rem;">
            <div class="filter-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="admin-search" class="filter-search__input" placeholder="Filtrer les films...">
            </div>
        </div>

        <!-- Tableau des films -->
        <div class="admin-table-wrap">
            <table class="admin-table" id="admin-films-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Genre</th>
                        <th>Réalisateur</th>
                        <th>Année</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($films as $film): ?>
                        <tr class="admin-table__row" data-title="<?= strtolower(htmlspecialchars($film['titre'])) ?>">
                            <td class="admin-table__id"><?= $film['id'] ?></td>
                            <td>
                                <img
                                    src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>"
                                    alt="<?= htmlspecialchars($film['titre']) ?>"
                                    class="admin-table__thumb"
                                    onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'"
                                >
                            </td>
                            <td class="admin-table__title"><?= htmlspecialchars($film['titre']) ?></td>
                            <td><span class="badge badge-genre"><?= htmlspecialchars($film['genre']) ?></span></td>
                            <td><?= htmlspecialchars($film['realisateur']) ?></td>
                            <td><?= $film['annee'] ?></td>
                            <td>
                                <div class="admin-table__actions">
                                    <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=detail&id=<?= $film['id'] ?>"
                                       class="btn btn-outline btn-xs" title="Voir la fiche">
                                        👁
                                    </a>
                                    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=edit&id=<?= $film['id'] ?>"
                                       class="btn btn-gold btn-xs" title="Modifier">
                                        ✏️
                                    </a>
                                    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=delete&id=<?= $film['id'] ?>"
                                       class="btn btn-danger btn-xs"
                                       title="Supprimer"
                                       onclick="return confirm('Supprimer définitivement « <?= addslashes($film['titre']) ?> » ?')">
                                        🗑
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
