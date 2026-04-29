<?php
// =====================================================
// CinéTrack - Model Avis (Commentaires & Notes)
// =====================================================
require_once __DIR__ . '/../config/database.php';

class Avis {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // -------------------------------------------------
    // Récupérer tous les avis d'un film
    // -------------------------------------------------
    public function getByFilm(int $filmId): array {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.nom AS auteur
             FROM avis a
             JOIN utilisateurs u ON u.id = a.utilisateur_id
             WHERE a.film_id = :fid
             ORDER BY a.date_avis DESC"
        );
        $stmt->execute([':fid' => $filmId]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------
    // Ajouter ou mettre à jour un avis
    // -------------------------------------------------
    public function addOrUpdate(int $userId, int $filmId, int $note, string $commentaire): bool {
        // Utilise VALUES(col) dans ON DUPLICATE KEY UPDATE pour éviter les
        // paramètres nommés en double (interdit avec ATTR_EMULATE_PREPARES = false).
        $stmt = $this->db->prepare(
            "INSERT INTO avis (utilisateur_id, film_id, note, commentaire)
             VALUES (:uid, :fid, :note, :com)
             ON DUPLICATE KEY UPDATE
                note        = VALUES(note),
                commentaire = VALUES(commentaire),
                date_avis   = NOW()"
        );
        return $stmt->execute([
            ':uid'  => $userId,
            ':fid'  => $filmId,
            ':note' => max(1, min(5, $note)),
            ':com'  => htmlspecialchars(trim($commentaire)),
        ]);
    }

    // -------------------------------------------------
    // Vérifier si l'utilisateur a déjà laissé un avis
    // -------------------------------------------------
    public function getUserAvis(int $userId, int $filmId): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM avis WHERE utilisateur_id = :uid AND film_id = :fid"
        );
        $stmt->execute([':uid' => $userId, ':fid' => $filmId]);
        return $stmt->fetch();
    }

    // -------------------------------------------------
    // Supprimer un avis
    // -------------------------------------------------
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM avis WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------
    // Note moyenne d'un film
    // -------------------------------------------------
    public function getAverage(int $filmId): float {
        $stmt = $this->db->prepare(
            "SELECT AVG(note) FROM avis WHERE film_id = :fid"
        );
        $stmt->execute([':fid' => $filmId]);
        return round((float)$stmt->fetchColumn(), 1);
    }
}
