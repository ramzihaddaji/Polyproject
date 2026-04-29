<?php
// =====================================================
// CinéTrack - Model Utilisateur
// =====================================================
require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // -------------------------------------------------
    // Inscrire un nouvel utilisateur
    // -------------------------------------------------
    public function register(string $nom, string $email, string $password): bool {
        // Vérifier si l'email existe déjà
        if ($this->emailExists($email)) {
            return false;
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
             VALUES (:nom, :email, :mdp, 'user')"
        );
        return $stmt->execute([
            ':nom'   => htmlspecialchars(trim($nom)),
            ':email' => strtolower(trim($email)),
            ':mdp'   => $hash,
        ]);
    }

    // -------------------------------------------------
    // Authentifier un utilisateur
    // -------------------------------------------------
    public function login(string $email, string $password): array|false {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    // -------------------------------------------------
    // Récupérer un utilisateur par email
    // -------------------------------------------------
    public function getByEmail(string $email): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => strtolower(trim($email))]);
        return $stmt->fetch();
    }

    // -------------------------------------------------
    // Récupérer un utilisateur par ID
    // -------------------------------------------------
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nom, email, role, created_at FROM utilisateurs WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // -------------------------------------------------
    // Vérifier si l'email est déjà utilisé
    // -------------------------------------------------
    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM utilisateurs WHERE email = :email"
        );
        $stmt->execute([':email' => strtolower(trim($email))]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // -------------------------------------------------
    // Récupérer tous les utilisateurs (admin)
    // -------------------------------------------------
    public function getAll(): array {
        return $this->db->query(
            "SELECT id, nom, email, role, created_at FROM utilisateurs ORDER BY created_at DESC"
        )->fetchAll();
    }

    // -------------------------------------------------
    // Compter les utilisateurs
    // -------------------------------------------------
    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
    }
}
