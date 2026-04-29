<?php
// =====================================================
// CinéTrack - Page Connexion
// =====================================================
$pageTitle = 'Connexion';
include __DIR__ . '/../layouts/header.php';
?>

<section class="auth-section">
    <div class="auth-card">

        <!-- En-tête -->
        <div class="auth-card__header">
            <div class="auth-card__icon">🎬</div>
            <h1 class="auth-card__title">Bon retour !</h1>
            <p class="auth-card__subtitle">Connectez-vous pour accéder à votre espace CinéTrack.</p>
        </div>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form action="<?= BASE_URL ?>/controllers/AuthController.php?action=doLogin" method="POST" class="auth-form" novalidate>

            <div class="form-group">
                <label class="form-label" for="email">Adresse email</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="votre@email.com"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="form-input-wrap">
                    <svg class="form-input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="form-toggle-pw" id="toggle-password" aria-label="Afficher le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-gold btn-full btn-lg">
                Connexion
            </button>

        </form>

        <!-- Lien inscription -->
        <div class="auth-card__footer">
            <p>Pas encore de compte ?
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register" class="link-gold">Créer un compte</a>
            </p>
        </div>

        <!-- Compte de démo -->
        <div class="demo-credentials">
            <div class="demo-credentials__title">Comptes de démonstration</div>
            <div class="demo-credentials__list">
                <div class="demo-item">
                    <span class="badge badge-admin">Admin</span>
                    <code>admin@cinetrack.com</code>
                    <span>/ password</span>
                </div>
                <div class="demo-item">
                    <span class="badge badge-user">User</span>
                    <code>jean@example.com</code>
                    <span>/ password</span>
                </div>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
