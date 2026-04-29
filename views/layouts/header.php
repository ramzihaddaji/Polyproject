<?php
// =====================================================
// CinéTrack - Header / Navbar
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

// Compter les films dans la watchlist (si connecté)
$watchlistCount = 0;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../models/Wishlist.php';
    $wl = new Wishlist();
    $watchlistCount = $wl->countByUser($_SESSION['user_id']);
}

// Initiales de l'utilisateur pour l'avatar
$initiales = '';
if (isset($_SESSION['user_nom'])) {
    $parts = explode(' ', $_SESSION['user_nom']);
    foreach ($parts as $p) {
        $initiales .= strtoupper(mb_substr($p, 0, 1));
    }
    $initiales = mb_substr($initiales, 0, 2);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'CinéTrack' ?> — Votre Cinéma Personnel</title>
    <meta name="description" content="CinéTrack — Cataloguez vos films, gérez votre watchlist et partagez vos avis.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Bebas+Neue&display=swap" rel="stylesheet">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<header class="navbar" id="navbar">
    <div class="navbar__inner">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="navbar__logo">
            <span class="logo-icon">🎬</span>
            Ciné<span class="logo-accent">Track</span>
        </a>

        <!-- Search bar -->
        <form class="navbar__search" action="<?= BASE_URL ?>/controllers/FilmController.php" method="GET">
            <input type="hidden" name="action" value="index">
            <input
                type="text"
                id="nav-search-input"
                name="search"
                class="navbar__search-input"
                placeholder="Rechercher un film, réalisateur..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                autocomplete="off"
            >
            <button type="submit" class="navbar__search-btn" aria-label="Rechercher">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
        </form>

        <!-- Navigation Links -->
        <nav class="navbar__links" id="nav-links">
            <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index" class="nav-link <?= (strpos($_SERVER['REQUEST_URI'] ?? '', 'FilmController') !== false && ($_GET['action'] ?? '') !== 'detail') ? 'active' : '' ?>">
                Catalogue
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index" class="nav-link nav-link--watchlist">
                    Ma Watchlist
                    <?php if ($watchlistCount > 0): ?>
                        <span class="nav-badge"><?= $watchlistCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="nav-link nav-link--admin">
                    Admin
                </a>
            <?php endif; ?>
        </nav>

        <!-- Auth / Profil -->
        <div class="navbar__auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Utilisateur connecté -->
                <div class="nav-user" id="nav-user-menu">
                    <button class="nav-user__btn" id="nav-user-btn" aria-expanded="false">
                        <div class="nav-user__avatar"><?= htmlspecialchars($initiales) ?></div>
                        <span class="nav-user__name"><?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                        <svg class="nav-user__arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="nav-dropdown" id="nav-dropdown">
                        <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index" class="nav-dropdown__item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            Ma Watchlist
                        </a>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index" class="nav-dropdown__item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            Administration
                        </a>
                        <?php endif; ?>
                        <div class="nav-dropdown__sep"></div>
                        <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout" class="nav-dropdown__item nav-dropdown__item--danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Déconnexion
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Non connecté -->
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login" class="btn btn-outline btn-sm">Connexion</a>
                <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register" class="btn btn-gold btn-sm">Inscription</a>
            <?php endif; ?>
        </div>

        <!-- Burger menu mobile -->
        <button class="navbar__burger" id="nav-burger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

    </div>
</header>

<!-- Overlay mobile -->
<div class="nav-overlay" id="nav-overlay"></div>

<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">