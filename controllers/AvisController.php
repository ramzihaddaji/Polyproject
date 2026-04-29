<?php
// =====================================================
// CinéTrack - Controller Avis (notes & commentaires)
// =====================================================
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Avis.php';

// Protéger la route : connecté obligatoire
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
    exit;
}

$avisModel = new Avis();
$userId    = (int)$_SESSION['user_id'];
$action    = $_GET['action'] ?? '';

switch ($action) {

    // --------------------------------------------------
    // Soumettre un avis (POST)
    // --------------------------------------------------
    case 'submit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filmId      = (int)($_POST['film_id']     ?? 0);
            $note        = (int)($_POST['note']        ?? 0);
            $commentaire = trim($_POST['commentaire']  ?? '');

            if ($filmId > 0 && $note >= 1 && $note <= 5) {
                $avisModel->addOrUpdate($userId, $filmId, $note, $commentaire);
            }

            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=detail&id=$filmId");
        } else {
            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
        }
        exit;

    // --------------------------------------------------
    // Supprimer son propre avis
    // --------------------------------------------------
    case 'delete':
        $avisId = (int)($_GET['id']      ?? 0);
        $filmId = (int)($_GET['film_id'] ?? 0);

        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($avisId > 0) {
            $stmt = Database::getConnection()->prepare(
                "DELETE FROM avis WHERE id = :id AND utilisateur_id = :uid"
            );
            $stmt->execute([':id' => $avisId, ':uid' => $userId]);
        }

        header("Location: " . BASE_URL . "/controllers/FilmController.php?action=detail&id=$filmId");
        exit;

    default:
        header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
        exit;
}
