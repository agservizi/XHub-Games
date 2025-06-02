<?php
/**
 * Xbox Games Catalog - Installazione Automatica
 * Crea le tabelle necessarie nel database e verifica la connessione
 */

// Carica variabili ambiente dal file .env
function loadEnv($file = '.env') {
    if (!file_exists($file)) return;
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

loadEnv(__DIR__ . '/.env');

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$db   = $_ENV['DB_NAME'] ?? 'xbox_games_catalog';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? '3306';

try {
    echo "<h2>🔌 Connessione al database...</h2>";
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<p style='color:lime'>✅ Connessione riuscita!</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Errore di connessione: ".$e->getMessage()."</p>";
    exit;
}

// SQL per la tabella games
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `cover_url` VARCHAR(512) DEFAULT NULL,
  `release_year` INT DEFAULT NULL,
  `genre` VARCHAR(100) DEFAULT NULL,
  `developer` VARCHAR(100) DEFAULT NULL,
  `publisher` VARCHAR(100) DEFAULT NULL,
  `language` VARCHAR(50) DEFAULT NULL,
  `support_type` VARCHAR(50) DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT NULL,
  `personal_rating` DECIMAL(3,1) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

try {
    $pdo->exec($sql);
    echo "<p style='color:lime'>✅ Tabella <b>games</b> creata o già esistente.</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Errore creazione tabella: ".$e->getMessage()."</p>";
    exit;
}

echo "<h3>Installazione completata! Puoi ora usare l'applicazione 🎮</h3>";
?>
