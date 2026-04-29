<?php
// =====================================================
// CinéTrack - Model Film
// =====================================================
require_once __DIR__ . '/../config/database.php';

class Film {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // -------------------------------------------------
    // Récupérer tous les films (avec filtre optionnel)
    // -------------------------------------------------
    public function getAll(string $genre = '', string $annee = '', string $search = ''): array {
        $sql = "SELECT f.*,
                    COALESCE(AVG(a.note), 0) AS note_moyenne,
                    COUNT(a.id) AS nb_avis
                FROM films f
                LEFT JOIN avis a ON a.film_id = f.id
                WHERE 1=1";
        $params = [];

        if (!empty($genre)) {
            $sql .= " AND f.genre = :genre";
            $params[':genre'] = $genre;
        }
        if (!empty($annee)) {
            $sql .= " AND f.annee = :annee";
            $params[':annee'] = (int)$annee;
        }
        if (!empty($search)) {
            $sql .= " AND (f.titre LIKE :search OR f.realisateur LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " GROUP BY f.id ORDER BY f.annee DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------
    // Récupérer un film par son ID
    // -------------------------------------------------
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT f.*,
                COALESCE(AVG(a.note), 0) AS note_moyenne,
                COUNT(a.id) AS nb_avis
             FROM films f
             LEFT JOIN avis a ON a.film_id = f.id
             WHERE f.id = :id
             GROUP BY f.id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // -------------------------------------------------
    // Récupérer tous les genres distincts
    // -------------------------------------------------
    public function getGenres(): array {
        $stmt = $this->db->query("SELECT DISTINCT genre FROM films ORDER BY genre ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // -------------------------------------------------
    // Récupérer toutes les années distinctes
    // -------------------------------------------------
    public function getAnnees(): array {
        $stmt = $this->db->query("SELECT DISTINCT annee FROM films ORDER BY annee DESC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // -------------------------------------------------
    // Ajouter un film (admin)
    // -------------------------------------------------
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO films (titre, genre, synopsis, realisateur, annee, image)
             VALUES (:titre, :genre, :synopsis, :realisateur, :annee, :image)"
        );
        return $stmt->execute([
            ':titre'       => htmlspecialchars(trim($data['titre'])),
            ':genre'       => htmlspecialchars(trim($data['genre'])),
            ':synopsis'    => htmlspecialchars(trim($data['synopsis'])),
            ':realisateur' => htmlspecialchars(trim($data['realisateur'])),
            ':annee'       => (int)$data['annee'],
            ':image'       => $data['image'] ?? 'default.jpg',
        ]);
    }

    // -------------------------------------------------
    // Modifier un film (admin)
    // -------------------------------------------------
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE films SET
                titre       = :titre,
                genre       = :genre,
                synopsis    = :synopsis,
                realisateur = :realisateur,
                annee       = :annee,
                image       = :image
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'          => $id,
            ':titre'       => htmlspecialchars(trim($data['titre'])),
            ':genre'       => htmlspecialchars(trim($data['genre'])),
            ':synopsis'    => htmlspecialchars(trim($data['synopsis'])),
            ':realisateur' => htmlspecialchars(trim($data['realisateur'])),
            ':annee'       => (int)$data['annee'],
            ':image'       => $data['image'] ?? 'default.jpg',
        ]);
    }

    // -------------------------------------------------
    // Supprimer un film (admin)
    // -------------------------------------------------
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM films WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------
    // Compter le nombre total de films
    // -------------------------------------------------
    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM films")->fetchColumn();
    }
}
