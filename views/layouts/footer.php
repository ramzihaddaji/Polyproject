</main>

<!-- ============ FOOTER ============ -->
<footer class="site-footer">
    <div class="footer-inner">

        <!-- Logo + Description -->
        <div class="footer-brand">
            <div class="footer-logo">🎬 Ciné<span class="logo-accent">Track</span></div>
            <p class="footer-tagline">
                Votre catalogue cinéma personnel. Suivez, notez et organisez tous vos films en un seul endroit.
            </p>
            <div class="footer-socials">
                <a href="#" class="social-btn" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="#" class="social-btn" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                </a>
                <a href="#" class="social-btn" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
            </div>
        </div>

        <!-- Liens Navigation -->
        <div class="footer-links">
            <div class="footer-col">
                <div class="footer-col__title">Navigation</div>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index">Catalogue</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/controllers/WishlistController.php?action=index">Ma Watchlist</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/controllers/AdminController.php?action=index">Administration</a>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <div class="footer-col__title">Compte</div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=logout">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=login">Connexion</a>
                    <a href="<?= BASE_URL ?>/controllers/AuthController.php?action=register">Inscription</a>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <div class="footer-col__title">Genres</div>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index&genre=Action">Action</a>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index&genre=Drame">Drame</a>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index&genre=Science-Fiction">Sci-Fi</a>
                <a href="<?= BASE_URL ?>/controllers/FilmController.php?action=index&genre=Thriller">Thriller</a>
            </div>
        </div>

    </div>

    <!-- Bas du footer -->
    <div class="footer-bottom">
        <div>© <?= date("Y") ?> CinéTrack. Tous droits réservés.</div>
        <div class="footer-bottom__right">
            <span class="tech-badge">PHP</span>
            <span class="tech-badge">MySQL</span>
            <span class="tech-badge">HTML</span>
            <span class="tech-badge">CSS</span>
            <span class="tech-badge">JS</span>
        </div>
    </div>
</footer>

<!-- ============ JAVASCRIPT ============ -->
<script src="<?= BASE_URL ?>/assets/js/script.js"></script>

</body>
</html>