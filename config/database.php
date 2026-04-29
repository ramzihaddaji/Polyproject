<?php
// =====================================================
// CinéTrack - Configuration de la base de données
// =====================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'cinetrack');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// URL de base de l'application (adapter selon votre config)
define('BASE_URL', 'http://localhost/projectWebPhp');

// Dossier des images uploadées
define('IMG_PATH', BASE_URL . '/assets/img/');

/**
 * Classe de connexion PDO (Singleton)
 * Garantit une seule connexion par requête
 */
class Database {
    private static ?PDO $instance = null;

    /**
     * Retourne l'instance unique de la connexion PDO
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                die("<div style='color:red;font-family:sans-serif;padding:20px;'>
                    <h3>Erreur de connexion à la base de données</h3>
                    <p>" . $e->getMessage() . "</p>
                    <p>Vérifiez vos paramètres dans config/database.php</p>
                </div>");
            }
        }
        return self::$instance;
    }
}
