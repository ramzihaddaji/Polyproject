<?php
// =====================================================
// CinéTrack — Point d'entrée principal
// Redirige vers le contrôleur Films
// =====================================================
header("Location: controllers/FilmController.php?action=index");
exit;
