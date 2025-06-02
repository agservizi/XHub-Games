<?php
/**
 * Database Connection Test Script
 * Xbox Games Catalog
 */

require_once 'config/database.php';

echo "🎮 Xbox Games Catalog - Database Connection Test\n";
echo "================================================\n\n";

try {
    $database = new Database();
    $result = $database->testConnection();
    
    if ($result['success']) {
        echo "✅ SUCCESS: Database connection established!\n\n";
        echo "Connection Details:\n";
        echo "- Host: " . $result['host'] . "\n";
        echo "- Port: " . $result['port'] . "\n";
        echo "- Database: " . $result['database'] . "\n";
        echo "- Message: " . $result['message'] . "\n\n";
        
        // Test a simple query
        $conn = $database->getConnection();
        
        // Check if games table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE 'games'");
        $stmt->execute();
        $tableExists = $stmt->fetch();
        
        if ($tableExists) {
            echo "📋 Games table exists\n";
            
            // Count games
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM games");
            $stmt->execute();
            $count = $stmt->fetch();
            
            echo "🎯 Current games in database: " . $count['count'] . "\n";
        } else {
            echo "⚠️  Games table does not exist - run setup.php to create tables\n";
        }
        
    } else {
        echo "❌ ERROR: Database connection failed!\n\n";
        echo "Error Details:\n";
        echo "- Host: " . $result['host'] . "\n";
        echo "- Port: " . $result['port'] . "\n";
        echo "- Database: " . $result['database'] . "\n";
        echo "- Error: " . $result['message'] . "\n\n";
        
        echo "Troubleshooting Tips:\n";
        echo "1. Check if the database server is running\n";
        echo "2. Verify credentials in .env file\n";
        echo "3. Ensure the database exists\n";
        echo "4. Check firewall settings\n";
        echo "5. Verify network connectivity\n";
    }
    
} catch (Exception $e) {
    echo "💥 EXCEPTION: " . $e->getMessage() . "\n";
    echo "\nPlease check your .env file configuration.\n";
}

echo "\n================================================\n";
echo "Test completed.\n";
?>
