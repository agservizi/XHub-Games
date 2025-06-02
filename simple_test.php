<?php
/**
 * Simple Database Test
 */

// Load environment variables
$envPath = '.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

echo "Environment loaded:\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'not set') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'not set') . "\n";
echo "DB_PASS: " . (isset($_ENV['DB_PASS']) ? '[HIDDEN]' : 'not set') . "\n";

try {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";port=3306;dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "\nConnection SUCCESS!\n";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "MySQL Version: " . $result['version'] . "\n";
    
} catch (Exception $e) {
    echo "\nConnection FAILED: " . $e->getMessage() . "\n";
}
?>
