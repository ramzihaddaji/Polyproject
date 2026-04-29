<?php
// =====================================================
// CinéTrack - Formulaire Ajout / Modification (Admin)
// =====================================================
$isEdit    = isset($film); // true si modification, false si création
$pageTitle = $isEdit ? 'Modifier le film' : 'Ajouter un film';
include __DIR__ . '/../layouts/header.php';
?>

<section class="section">
    <div class="container container--narrow">

        <!-- En-tête -->
        <div class="section__header">
            <div>
                <h1 class="section__title"><?= $isEdit ? 'Modifier un film' : 'Ajouter un film' ?></h1>
                <p class="section__subtitle">
                    <?= $isEdit ? 'Modifiez les informations du film.' : 'Remplissez le formulaire pour ajouter un nouveau film au catalogue.' ?>
                </p>
            </div>
            <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="btn btn-outline">
                ← Retour
            </a>
        </div>

        <!-- Message erreur -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form
            action="<?= BASE_URL ?>/controllers/AdminController.php?action=<?= $isEdit ? 'doEdit' : 'doCreate' ?>"
            method="POST"
            class="film-form card"
            enctype="multipart/form-data"
        >
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $film['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">

                <!-- Colonne gauche -->
                <div class="form-col">

                    <div class="form-group">
                        <label class="form-label" for="titre">Titre du film <span class="required">*</span></label>
                        <input
                            type="text"
                            id="titre"
                            name="titre"
                            class="form-control"
                            placeholder="Ex: Inception"
                            value="<?= $isEdit ? htmlspecialchars($film['titre']) : '' ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="genre">Genre <span class="required">*</span></label>
                        <select id="genre" name="genre" class="form-select" required>
                            <option value="">Sélectionner un genre</option>
                            <?php
                            $genres_list = ['Action', 'Animation', 'Aventure', 'Biographie', 'Comédie', 'Crime', 'Documentaire', 'Drame', 'Fantaisie', 'Horreur', 'Musical', 'Policier', 'Romance', 'Science-Fiction', 'Thriller', 'Western'];
                            foreach ($genres_list as $g):
                                $selected = ($isEdit && $film['genre'] === $g) ? 'selected' : '';
                            ?>
                                <option value="<?= $g ?>" <?= $selected ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="realisateur">Réalisateur</label>
                        <input
                            type="text"
                            id="realisateur"
                            name="realisateur"
                            class="form-control"
                            placeholder="Ex: Christopher Nolan"
                            value="<?= $isEdit ? htmlspecialchars($film['realisateur']) : '' ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="annee">Année <span class="required">*</span></label>
                        <input
                            type="number"
                            id="annee"
                            name="annee"
                            class="form-control"
                            placeholder="Ex: 2010"
                            min="1888"
                            max="<?= date('Y') + 2 ?>"
                            value="<?= $isEdit ? $film['annee'] : '' ?>"
                            required
                        >
                    </div>

                </div>

                <!-- Colonne droite -->
                <div class="form-col">

                    <div class="form-group">
                        <label class="form-label" for="synopsis">Synopsis</label>
                        <textarea
                            id="synopsis"
                            name="synopsis"
                            class="form-textarea"
                            rows="6"
                            placeholder="Décrivez l'histoire du film..."
                        ><?= $isEdit ? htmlspecialchars($film['synopsis']) : '' ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Image (affiche)</label>
                        <div class="file-upload" id="file-upload-zone">
                            <input type="file" id="image" name="image" class="file-upload__input" accept="image/*">
                            <div class="file-upload__visual">
                                <?php if ($isEdit && $film['image'] !== 'default.jpg'): ?>
                                    <img id="image-preview" src="<?= BASE_URL ?>/assets/img/<?= htmlspecialchars($film['image']) ?>" alt="Aperçu">
                                <?php else: ?>
                                    <div class="file-upload__placeholder" id="file-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        <p>Glissez une image ou <span class="link-gold">cliquez pour parcourir</span></p>
                                        <small>JPG, PNG, WEBP</small>
                                        <img id="image-preview" src="" alt="Aperçu" style="display:none; max-height: 150px; margin-top: 0.5rem; border-radius: 8px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($isEdit): ?>
                            <small class="form-hint">Laissez vide pour conserver l'image actuelle.</small>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Boutons -->
            <div class="form-actions">
                <button type="submit" class="btn btn-gold btn-lg">
                    <?= $isEdit ? '💾 Enregistrer les modifications' : '✅ Ajouter le film' ?>
                </button>
                <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="btn btn-outline btn-lg">
                    Annuler
                </a>
            </div>

        </form>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
