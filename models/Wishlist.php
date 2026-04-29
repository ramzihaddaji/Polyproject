<?php
// =====================================================
// CinéTrack - Model Wishlist (Watchlist)
// =====================================================
require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // -------------------------------------------------
    // Récupérer la watchlist complète d'un utilisateur
    // -------------------------------------------------
    public function getByUser(int $userId, string $statut = ''): array {
        $sql = "SELECT w.*, f.titre, f.genre, f.annee, f.image, f.realisateur,
                    COALESCE(AVG(a.note), 0) AS note_moyenne
                FROM wishlist w
                JOIN films f ON f.id = w.film_id
                LEFT JOIN avis a ON a.film_id = f.id
                WHERE w.utilisateur_id = :uid";
        $params = [':uid' => $userId];

        if (!empty($statut)) {
            $sql .= " AND w.statut = :statut";
            $params[':statut'] = $statut;
        }

        $sql .= " GROUP BY w.id, f.id ORDER BY w.date_ajout DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------
    // Ajouter un film à la watchlist
    // -------------------------------------------------
    public function add(int $userId, int $filmId): bool {
        // INSERT IGNORE évite les doublons grâce à la clé unique
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO wishlist (utilisateur_id, film_id, statut)
             VALUES (:uid, :fid, 'a_voir')"
        );
        return $stmt->execute([':uid' => $userId, ':fid' => $filmId]);
    }

    // -------------------------------------------------
    // Changer le statut (a_voir ↔ vu)
    // -------------------------------------------------
    public function updateStatut(int $userId, int $filmId, string $statut): bool {
        $stmt = $this->db->prepare(
            "UPDATE wishlist SET statut = :statut
             WHERE utilisateur_id = :uid AND film_id = :fid"
        );
        return $stmt->execute([
            ':statut' => $statut,
            ':uid'    => $userId,
            ':fid'    => $filmId,
        ]);
    }

    // -------------------------------------------------
    // Supprimer un film de la watchlist
    // -------------------------------------------------
    public function remove(int $userId, int $filmId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM wishlist WHERE utilisateur_id = :uid AND film_id = :fid"
        );
        return $stmt->execute([':uid' => $userId, ':fid' => $filmId]);
    }

    // -------------------------------------------------
    // Vérifier si un film est dans la watchlist
    // -------------------------------------------------
    public function isInWatchlist(int $userId, int $filmId): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM wishlist WHERE utilisateur_id = :uid AND film_id = :fid"
        );
        $stmt->execute([':uid' => $userId, ':fid' => $filmId]);
        return $stmt->fetch();
    }

    // -------------------------------------------------
    // Compter les films dans la watchlist
    // -------------------------------------------------
    public function countByUser(int $userId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM wishlist WHERE utilisateur_id = :uid"
        );
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
