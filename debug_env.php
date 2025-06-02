<?php
/**
 * Environment Debug Script
 */

echo "=== Environment Debug ===\n\n";

// Check if .env file exists
$envPath = '.env';
echo "Checking .env file at: " . realpath($envPath) . "\n";

if (file_exists($envPath)) {
    echo "✅ .env file exists\n";
    
    // Load and display env vars
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo "\n📋 Loading environment variables:\n";
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            echo "   (comment) $line\n";
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            $_ENV[$name] = $value;
            
            // Hide password
            $displayValue = ($name === 'DB_PASS') ? '[HIDDEN]' : $value;
            echo "   $name = $displayValue\n";
        }
    }
    
    echo "\n🔌 Testing database connection with loaded variables:\n";
    
    try {
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";port=3306;dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4";
        echo "DSN: $dsn\n";
        echo "User: " . $_ENV['DB_USER'] . "\n";
        
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10
        ]);
        
        echo "✅ Connection successful!\n";
        
        // Test query
        $stmt = $pdo->query("SELECT VERSION() as version");
        $result = $stmt->fetch();
        echo "📊 MySQL Version: " . $result['version'] . "\n";
        
        // Check if database exists and is accessible
        $stmt = $pdo->query("SELECT DATABASE() as current_db");
        $result = $stmt->fetch();
        echo "🗃️ Current Database: " . $result['current_db'] . "\n";
        
    } catch (Exception $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n";
        
        // Additional debug info
        echo "\n🔍 Debug information:\n";
        echo "Error Code: " . $e->getCode() . "\n";
        
        if ($e->getCode() == 1045) {
            echo "   → This is an authentication error. Check username/password.\n";
        } elseif ($e->getCode() == 2002) {
            echo "   → This is a connection error. Check host/port.\n";
        } elseif ($e->getCode() == 1049) {
            echo "   → Database doesn't exist. Check database name.\n";
        }
    }
    
} else {
    echo "❌ .env file not found\n";
    echo "Current working directory: " . getcwd() . "\n";
    echo "Files in current directory:\n";
    foreach (glob('*') as $file) {
        echo "   - $file\n";
    }
}
?>
