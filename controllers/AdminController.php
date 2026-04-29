<?php
// =====================================================
// CinéTrack - Controller Admin (CRUD films)
// =====================================================
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Film.php';
require_once __DIR__ . '/../models/User.php';

// Vérification de rôle admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
    exit;
}

$filmModel = new Film();
$userModel = new User();
$action    = $_GET['action'] ?? 'index';

switch ($action) {

    // --------------------------------------------------
    // Dashboard admin
    // --------------------------------------------------
    case 'index':
        $films      = $filmModel->getAll();
        $nb_films   = $filmModel->count();
        $nb_users   = $userModel->count();
        $message    = $_SESSION['admin_msg'] ?? '';
        unset($_SESSION['admin_msg']);
        include __DIR__ . '/../views/admin/index.php';
        break;

    // --------------------------------------------------
    // Formulaire ajout film
    // --------------------------------------------------
    case 'create':
        $error = $_SESSION['admin_error'] ?? '';
        unset($_SESSION['admin_error']);
        include __DIR__ . '/../views/admin/form.php';
        break;

    // --------------------------------------------------
    // Traiter ajout film (POST)
    // --------------------------------------------------
    case 'doCreate':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=create");
            exit;
        }

        $data  = $_POST;
        $image = 'default.jpg';

        // Gestion upload image
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = uniqid('film_') . '.' . $ext;
                $dest     = __DIR__ . '/../assets/img/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image = $filename;
                }
            }
        }

        $data['image'] = $image;

        if (empty($data['titre']) || empty($data['genre']) || empty($data['annee'])) {
            $_SESSION['admin_error'] = "Titre, genre et année sont obligatoires.";
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=create");
        } else {
            $filmModel->create($data);
            $_SESSION['admin_msg'] = "Film ajouté avec succès !";
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
        }
        exit;

    // --------------------------------------------------
    // Formulaire modification film
    // --------------------------------------------------
    case 'edit':
        $id   = (int)($_GET['id'] ?? 0);
        $film = $filmModel->getById($id);
        if (!$film) {
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
            exit;
        }
        $error = $_SESSION['admin_error'] ?? '';
        unset($_SESSION['admin_error']);
        include __DIR__ . '/../views/admin/form.php';
        break;

    // --------------------------------------------------
    // Traiter modification film (POST)
    // --------------------------------------------------
    case 'doEdit':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
            exit;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $data = $_POST;
        $film = $filmModel->getById($id);

        if (!$film) {
            header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
            exit;
        }

        $data['image'] = $film['image']; // garder l'ancienne image par défaut

        // Gestion upload nouvelle image
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = uniqid('film_') . '.' . $ext;
                $dest     = __DIR__ . '/../assets/img/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $data['image'] = $filename;
                }
            }
        }

        $filmModel->update($id, $data);
        $_SESSION['admin_msg'] = "Film modifié avec succès !";
        header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
        exit;

    // --------------------------------------------------
    // Supprimer film
    // --------------------------------------------------
    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $filmModel->delete($id);
            $_SESSION['admin_msg'] = "Film supprimé.";
        }
        header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
        exit;

    default:
        header("Location: " . BASE_URL . "/controllers/AdminController.php?action=index");
        exit;
}
