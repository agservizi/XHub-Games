<?php
/**
 * Database Configuration for Xbox Games Catalog
 */

// Load environment variables from .env file
function loadEnvFile($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("Environment file not found: $filePath");
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

// Load .env file
$envPath = dirname(__DIR__) . '/.env';
loadEnvFile($envPath);

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $conn;
    
    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $this->db_name = $_ENV['DB_NAME'] ?? 'u427445037_xhub';
        $this->username = $_ENV['DB_USER'] ?? 'u427445037_xhub';
        $this->password = $_ENV['DB_PASS'] ?? 'Giogiu2123@';
        $this->port = $_ENV['DB_PORT'] ?? '3306';
    }    /**
     * Get database connection
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ]
            );
            
            return $this->conn;
            
        } catch(PDOException $exception) {
            error_log("Database Connection Error: " . $exception->getMessage());
            
            // In production, don't expose sensitive database info
            if ($_ENV['APP_ENV'] === 'production') {
                throw new Exception("Database connection failed. Please try again later.");
            } else {
                throw new Exception("Connection error: " . $exception->getMessage());
            }
        }
    }
    
    /**
     * Test database connection
     */
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                return [
                    'success' => true,
                    'message' => 'Database connection successful',
                    'host' => $this->host,
                    'database' => $this->db_name,
                    'port' => $this->port
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'host' => $this->host,
                'database' => $this->db_name,
                'port' => $this->port
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Unknown connection error'
        ];
    }
}
?>
