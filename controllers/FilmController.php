<?php
// =====================================================
// CinéTrack - Controller Films
// =====================================================
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Film.php';
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Avis.php';

$filmModel    = new Film();
$wishlistModel = new Wishlist();
$avisModel    = new Avis();

$action = $_GET['action'] ?? 'index';

switch ($action) {

    // --------------------------------------------------
    // Liste catalogue
    // --------------------------------------------------
    case 'index':
        $genre  = $_GET['genre']  ?? '';
        $annee  = $_GET['annee']  ?? '';
        $search = $_GET['search'] ?? '';

        $films  = $filmModel->getAll($genre, $annee, $search);
        $genres = $filmModel->getGenres();
        $annees = $filmModel->getAnnees();

        include __DIR__ . '/../views/films/index.php';
        break;

    // --------------------------------------------------
    // Fiche détail film
    // --------------------------------------------------
    case 'detail':
        $id   = (int)($_GET['id'] ?? 0);
        $film = $filmModel->getById($id);

        if (!$film) {
            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
            exit;
        }

        $avis = $avisModel->getByFilm($id);

        // Vérifier si l'utilisateur a l'avis / dans watchlist
        $userAvis      = null;
        $watchlistInfo = null;
        if (isset($_SESSION['user_id'])) {
            $userAvis      = $avisModel->getUserAvis($_SESSION['user_id'], $id);
            $watchlistInfo = $wishlistModel->isInWatchlist($_SESSION['user_id'], $id);
        }

        include __DIR__ . '/../views/films/detail.php';
        break;

    default:
        header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
        exit;
}
