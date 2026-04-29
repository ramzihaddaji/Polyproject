<?php
// =====================================================
// CinéTrack - Controller Authentification
// =====================================================
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$action    = $_GET['action'] ?? 'login';

switch ($action) {

    // --------------------------------------------------
    // Afficher formulaire connexion
    // --------------------------------------------------
    case 'login':
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
            exit;
        }
        $error = $_SESSION['auth_error'] ?? '';
        unset($_SESSION['auth_error']);
        include __DIR__ . '/../views/auth/login.php';
        break;

    // --------------------------------------------------
    // Traiter connexion (POST)
    // --------------------------------------------------
    case 'doLogin':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['auth_error'] = "Veuillez remplir tous les champs.";
            header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
            exit;
        }

        $user = $userModel->login($email, $password);
        if ($user) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nom']  = $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
        } else {
            $_SESSION['auth_error'] = "Email ou mot de passe incorrect.";
            header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
        }
        exit;

    // --------------------------------------------------
    // Afficher formulaire inscription
    // --------------------------------------------------
    case 'register':
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
            exit;
        }
        $error   = $_SESSION['auth_error']   ?? '';
        $success = $_SESSION['auth_success'] ?? '';
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        include __DIR__ . '/../views/auth/register.php';
        break;

    // --------------------------------------------------
    // Traiter inscription (POST)
    // --------------------------------------------------
    case 'doRegister':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/controllers/AuthController.php?action=register");
            exit;
        }

        $nom      = trim($_POST['nom']      ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm']  ?? '');

        if (empty($nom) || empty($email) || empty($password)) {
            $_SESSION['auth_error'] = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = "Adresse email invalide.";
        } elseif (strlen($password) < 6) {
            $_SESSION['auth_error'] = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($password !== $confirm) {
            $_SESSION['auth_error'] = "Les mots de passe ne correspondent pas.";
        } elseif ($userModel->emailExists($email)) {
            $_SESSION['auth_error'] = "Cet email est déjà utilisé.";
        } else {
            $userModel->register($nom, $email, $password);
            $_SESSION['auth_success'] = "Compte créé avec succès ! Vous pouvez vous connecter.";
        }

        header("Location: " . BASE_URL . "/controllers/AuthController.php?action=register");
        exit;

    // --------------------------------------------------
    // Déconnexion
    // --------------------------------------------------
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "/controllers/FilmController.php?action=index");
        exit;

    default:
        header("Location: " . BASE_URL . "/controllers/AuthController.php?action=login");
        exit;
}
