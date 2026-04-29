<?php
// =====================================================
// CinéTrack - Page Inscription
// =====================================================
$pageTitle = 'Inscription';
include __DIR__ . '/../layouts/header.php';
?>

<section class="auth-section">
    <div class="auth-card">

        <!-- En-tête -->
        <div class="auth-card__header">
            <div class="auth-card__icon">🎥</div>
            <h1 class="auth-card__title">Rejoignez CinéTrack</h1>
            <p class="auth-card__subtitle">Créez votre compte et commencez à gérer vos films.</p>
        </div>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Message succès -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                <?= htmlspecialchars($success) ?>
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="link-gold">Se connecter maintenant →</a>
            </div>
        <?php endif; ?>

        <!-- Formulaire inscription -->
        <form action="<?= BASE_URL ?>/controllers/AuthController.php?action=doRegister" method="POST" class="auth-form" novalidate>

            <div class="form-group">
                <label class="form-label" for="nom">Nom complet</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        class="form-control"
                        placeholder="Jean Dupont"
                        required
                        autocomplete="name"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Adresse email</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="jean@example.com"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Mot de passe <small>(min. 6 caractères)</small></label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        minlength="6"
                        autocomplete="new-password"
                    >
                    <button type="button" class="form-toggle-pw" id="toggle-password" aria-label="Afficher le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <!-- Indicateur force du mot de passe -->
                <div class="password-strength" id="pw-strength">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirm">Confirmer le mot de passe</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                    <input
                        type="password"
                        id="confirm"
                        name="confirm"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-gold btn-full btn-lg">
                Créer mon compte
            </button>

        </form>

        <!-- Lien connexion -->
        <div class="auth-card__footer">
            <p>Déjà inscrit ?
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="link-gold">Se connecter</a>
            </p>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
