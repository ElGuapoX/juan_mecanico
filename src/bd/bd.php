<?php
/**
 * Database connection wrapper using mysqli.
 *
 * Improvements made:
 * - Reads credentials from environment variables (DB_HOST, DB_USER, DB_PASS, DB_NAME)
 *   with sensible defaults for local development.
 * - Enables mysqli exceptions for easier error handling.
 * - Sets connection charset to utf8mb4.
 * - Logs connection errors instead of printing them directly.
 */
class Database {
    private $host;
    private $user;
    private $password;
    private $database;
    public $conn;

    public function __construct() {
        // Use environment variables if provided, otherwise fall back to existing defaults.
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->database = getenv('DB_NAME') ?: 'juan_mecanico';
    }

    public function connect() {
        // Enable mysqli exceptions when available so we get exceptions instead of warnings.
        // Some PHP builds may not have mysqli enabled in the environment where this script
        // runs (for example minimal images or misconfigured CI). Guard the call so the
        // code still works in those environments.
        if (function_exists('mysqli_report')) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        }

        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            // Set proper charset
            $this->conn->set_charset('utf8mb4');
        } catch (Exception $e) {
            // Log detailed error server-side, but throw a generic exception to the app
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('No se pudo conectar a la base de datos. Revisa el registro de errores.');
        }

        return $this->conn;
    }
}
?>
