<?php
// =====================================================
// CinéTrack - Page Admin - Gestion des Films
// =====================================================
$pageTitle = 'Administration';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container admin-container">

    <!-- En-tête admin -->
    <div class="page-header">
        <h1 class="page-title">⚙️ <span class="text-gold">Administration</span></h1>
        <p class="page-subtitle">Gérez le catalogue de films de CinéTrack</p>
    </div>

    <!-- Bouton ajouter un film -->
    <div class="admin-toolbar">
        <button class="btn btn-gold" onclick="toggleModal('addFilmModal')">+ Ajouter un film</button>
        <span class="films-count"><?= count($films) ?> film(s) dans le catalogue</span>
    </div>

    <!-- ===== TABLEAU DES FILMS ===== -->
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Affiche</th>
                    <th>Titre</th>
                    <th>Genre</th>
                    <th>Réalisateur</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($films as $film): ?>
                    <tr>
                        <td><?= $film['id'] ?></td>
                        <td>
                            <img src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>"
                                 alt="<?= htmlspecialchars($film['titre']) ?>"
                                 class="admin-thumb"
                                 onerror="this.src='<?= BASE_URL ?>/assets/img/default.jpg'">
                        </td>
                        <td><strong><?= htmlspecialchars($film['titre']) ?></strong></td>
                        <td><span class="genre-tag"><?= htmlspecialchars($film['genre']) ?></span></td>
                        <td><?= htmlspecialchars($film['realisateur'] ?? '-') ?></td>
                        <td><?= $film['annee'] ?></td>
                        <td class="admin-actions">
                            <!-- Modifier -->
                            <button class="btn btn-sm btn-outline"
                                    onclick='openEditModal(<?= json_encode($film) ?>)'>
                                ✏️ Modifier
                            </button>
                            <!-- Supprimer -->
                            <a href="<?= BASE_URL ?>/index.php?action=film_delete&id=<?= $film['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer définitivement ce film ?')">
                                🗑️ Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ===== MODAL AJOUT FILM ===== -->
<div class="modal-backdrop" id="addFilmModal">
    <div class="modal">
        <div class="modal-header">
            <h3>🎬 Ajouter un film</h3>
            <button class="modal-close" onclick="toggleModal('addFilmModal')">✕</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/index.php?action=film_store" enctype="multipart/form-data" class="modal-form">
            <?= filmFormFields() ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="toggleModal('addFilmModal')">Annuler</button>
                <button type="submit" class="btn btn-gold">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL MODIFICATION FILM ===== -->
<div class="modal-backdrop" id="editFilmModal">
    <div class="modal">
        <div class="modal-header">
            <h3>✏️ Modifier le film</h3>
            <button class="modal-close" onclick="toggleModal('editFilmModal')">✕</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/index.php?action=film_update" enctype="multipart/form-data" class="modal-form">
            <input type="hidden" name="id" id="editFilmId">
            <?= filmFormFields('edit') ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="toggleModal('editFilmModal')">Annuler</button>
                <button type="submit" class="btn btn-gold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php
/**
 * Génère les champs communs du formulaire film
 */
function filmFormFields(string $prefix = ''): string {
    $id = $prefix ? 'edit' : 'add';
    return '
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Titre *</label>
            <input type="text" name="titre" id="' . $id . 'Titre" class="form-input" required placeholder="Ex: Inception">
        </div>
        <div class="form-group">
            <label class="form-label">Genre *</label>
            <select name="genre" id="' . $id . 'Genre" class="form-input" required>
                <option value="">-- Choisir --</option>
                <option value="Action">Action</option>
                <option value="Science-Fiction">Science-Fiction</option>
                <option value="Drame">Drame</option>
                <option value="Comédie">Comédie</option>
                <option value="Thriller">Thriller</option>
                <option value="Horror">Horreur</option>
                <option value="Romance">Romance</option>
                <option value="Policier">Policier</option>
                <option value="Animation">Animation</option>
                <option value="Documentaire">Documentaire</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Réalisateur</label>
            <input type="text" name="realisateur" id="' . $id . 'Realisateur" class="form-input" placeholder="Ex: Christopher Nolan">
        </div>
        <div class="form-group">
            <label class="form-label">Année</label>
            <input type="number" name="annee" id="' . $id . 'Annee" class="form-input" min="1888" max="2030" placeholder="' . date('Y') . '">
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Synopsis</label>
        <textarea name="synopsis" id="' . $id . 'Synopsis" class="form-input" rows="4" placeholder="Résumé du film..."></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Image (JPG/PNG/WEBP)</label>
        <input type="file" name="image" class="form-input" accept="image/*">
    </div>';
}
?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
