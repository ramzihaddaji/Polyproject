<?php
// =====================================================
// CinéTrack - Controller Watchlist
// =====================================================
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Wishlist.php';

// Protéger la route : connecté obligatoire
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
    exit;
}

$wishlistModel = new Wishlist();
$userId        = (int)$_SESSION['user_id'];
$action        = $_GET['action'] ?? 'index';

switch ($action) {

    // --------------------------------------------------
    // Afficher la watchlist de l'utilisateur
    // --------------------------------------------------
    case 'index':
        $statut  = $_GET['statut'] ?? '';
        $films   = $wishlistModel->getByUser($userId, $statut);
        include __DIR__ . '/../views/watchlist/index.php';
        break;

    // --------------------------------------------------
    // Ajouter un film à la watchlist
    // --------------------------------------------------
    case 'add':
        $filmId = (int)($_GET['film_id'] ?? 0);
        if ($filmId > 0) {
            $wishlistModel->add($userId, $filmId);
        }
        // Retourner à la fiche film
        $redirect = $_SERVER['HTTP_REFERER'] ?? BASE_URL . "/controllers/FilmController.php?action=index";
        header("Location: $redirect");
        exit;

    // --------------------------------------------------
    // Changer le statut (a_voir <-> vu)
    // --------------------------------------------------
    case 'updateStatut':
        $filmId = (int)($_GET['film_id'] ?? 0);
        $statut = $_GET['statut'] ?? 'a_voir';

        if ($filmId > 0 && in_array($statut, ['a_voir', 'vu'])) {
            $wishlistModel->updateStatut($userId, $filmId, $statut);
        }
        header("Location: " . BASE_URL . "/controllers/WishlistController.php?action=index");
        exit;

    // --------------------------------------------------
    // Supprimer un film de la watchlist
    // --------------------------------------------------
    case 'remove':
        $filmId = (int)($_GET['film_id'] ?? 0);
        if ($filmId > 0) {
            $wishlistModel->remove($userId, $filmId);
        }
        header("Location: " . BASE_URL . "/controllers/WishlistController.php?action=index");
        exit;

    default:
        header("Location: " . BASE_URL . "/controllers/WishlistController.php?action=index");
        exit;
}
