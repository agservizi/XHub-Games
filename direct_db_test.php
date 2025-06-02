<?php
echo "=== Direct Database Connection Test ===\n";

// Hardcoded credentials for testing
$host = '127.0.0.1';
$dbname = 'u427445037_xhub';
$username = 'u427445037_xhub';
$password = 'Giogiu2123@';
$port = 3306;

echo "Host: $host\n";
echo "Database: $dbname\n";
echo "Username: $username\n";
echo "Password: [HIDDEN]\n";
echo "Port: $port\n\n";

echo "Attempting connection...\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 30,  // 30 seconds timeout
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    echo "✅ SUCCESS: Connected to database!\n";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version, DATABASE() as current_db");
    $result = $stmt->fetch();
    
    echo "MySQL Version: " . $result['version'] . "\n";
    echo "Current Database: " . $result['current_db'] . "\n";
    
    // Check if games table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'games'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Games table exists\n";
        
        // Count games
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM games");
        $count = $stmt->fetch();
        echo "Games count: " . $count['count'] . "\n";
    } else {
        echo "⚠️ Games table does not exist\n";
        echo "Available tables:\n";
        
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll();
        
        if (empty($tables)) {
            echo "   No tables found\n";
        } else {
            foreach ($tables as $table) {
                echo "   - " . array_values($table)[0] . "\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    switch ($e->getCode()) {
        case 1045:
            echo "   → Authentication failed. Check username and password.\n";
            break;
        case 2002:
            echo "   → Cannot connect to server. Check host and port.\n";
            break;
        case 1049:
            echo "   → Database does not exist.\n";
            break;
        case 1044:
            echo "   → Access denied for user to database.\n";
            break;
        default:
            echo "   → Unknown database error.\n";
    }
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
?>
