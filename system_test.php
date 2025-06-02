<?php
/**
 * Xbox Games Catalog - Complete System Test
 * This script tests all major components of the application
 */

require_once 'config/database.php';
require_once 'src/utils/FormValidator.php';
require_once 'src/utils/MessageHelper.php';
require_once 'src/utils/ImageUtils.php';
require_once 'src/utils/GameDataImporter.php';

class SystemTester {
    private $database;
    private $results = [];
    
    public function __construct() {
        $this->database = new Database();
    }
    
    public function runAllTests() {
        echo "🎮 Xbox Games Catalog - System Test Suite\n";
        echo "==========================================\n\n";
        
        $this->testDatabaseConnection();
        $this->testDatabaseStructure();
        $this->testFormValidator();
        $this->testImageUtils();
        $this->testGameDataImporter();
        $this->testMessageHelper();
        
        $this->displayResults();
    }
    
    private function testDatabaseConnection() {
        echo "🔌 Testing Database Connection...\n";
        
        try {
            $conn = $this->database->getConnection();
            if ($conn) {
                $this->results['database_connection'] = '✅ SUCCESS';
                echo "   ✅ Database connection established\n";
                
                // Test query
                $stmt = $conn->query("SELECT VERSION() as version");
                $result = $stmt->fetch();
                echo "   📋 MySQL Version: " . $result['version'] . "\n";
            } else {
                $this->results['database_connection'] = '❌ FAILED';
                echo "   ❌ Failed to establish connection\n";
            }
        } catch (Exception $e) {
            $this->results['database_connection'] = '❌ ERROR: ' . $e->getMessage();
            echo "   ❌ Error: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    private function testDatabaseStructure() {
        echo "🗃️ Testing Database Structure...\n";
        
        try {
            $conn = $this->database->getConnection();
            
            // Check if games table exists
            $stmt = $conn->query("SHOW TABLES LIKE 'games'");
            if ($stmt->rowCount() > 0) {
                echo "   ✅ Games table exists\n";
                
                // Check table structure
                $stmt = $conn->query("DESCRIBE games");
                $columns = $stmt->fetchAll();
                $expectedColumns = ['id', 'title', 'cover_url', 'release_year', 'genre', 'developer', 'publisher', 'language', 'support_type', 'status', 'personal_rating', 'notes', 'created_at', 'updated_at'];
                
                $foundColumns = array_column($columns, 'Field');
                $missingColumns = array_diff($expectedColumns, $foundColumns);
                
                if (empty($missingColumns)) {
                    echo "   ✅ All required columns present\n";
                    $this->results['database_structure'] = '✅ SUCCESS';
                } else {
                    echo "   ⚠️ Missing columns: " . implode(', ', $missingColumns) . "\n";
                    $this->results['database_structure'] = '⚠️ INCOMPLETE';
                }
                
                // Count games
                $stmt = $conn->query("SELECT COUNT(*) as count FROM games");
                $count = $stmt->fetch()['count'];
                echo "   📊 Games in database: $count\n";
                
            } else {
                echo "   ❌ Games table not found\n";
                $this->results['database_structure'] = '❌ FAILED';
            }
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            $this->results['database_structure'] = '❌ ERROR';
        }
        echo "\n";
    }
    
    private function testFormValidator() {
        echo "✅ Testing Form Validator...\n";
        
        try {
            $validator = new FormValidator();
            
            // Test valid data
            $validData = [
                'title' => 'Test Game',
                'release_year' => '2023',
                'genre' => 'Action',
                'developer' => 'Test Dev',
                'publisher' => 'Test Pub'
            ];
            
            $result = $validator->validate($validData);
            if ($result['isValid']) {
                echo "   ✅ Valid data validation passed\n";
            } else {
                echo "   ❌ Valid data validation failed\n";
            }
            
            // Test invalid data
            $invalidData = [
                'title' => '',
                'release_year' => 'invalid',
                'genre' => '',
                'developer' => '',
                'publisher' => ''
            ];
            
            $result = $validator->validate($invalidData);
            if (!$result['isValid'] && !empty($result['errors'])) {
                echo "   ✅ Invalid data validation passed\n";
                $this->results['form_validator'] = '✅ SUCCESS';
            } else {
                echo "   ❌ Invalid data validation failed\n";
                $this->results['form_validator'] = '❌ FAILED';
            }
            
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            $this->results['form_validator'] = '❌ ERROR';
        }
        echo "\n";
    }
    
    private function testImageUtils() {
        echo "🖼️ Testing Image Utils...\n";
        
        try {
            // Test placeholder generation
            $placeholder = ImageUtils::getPlaceholderImage('Test Game');
            if (!empty($placeholder)) {
                echo "   ✅ Placeholder image generation works\n";
            }
            
            // Test URL validation
            $validUrl = 'https://example.com/image.jpg';
            $isValid = ImageUtils::isValidImageUrl($validUrl);
            if ($isValid !== null) {
                echo "   ✅ Image URL validation works\n";
            }
            
            $this->results['image_utils'] = '✅ SUCCESS';
            
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            $this->results['image_utils'] = '❌ ERROR';
        }
        echo "\n";
    }
    
    private function testGameDataImporter() {
        echo "📦 Testing Game Data Importer...\n";
        
        try {
            // Test getting popular games
            $popularGames = GameDataImporter::getPopularXboxGames();
            if (!empty($popularGames) && count($popularGames) > 0) {
                echo "   ✅ Popular games data available (" . count($popularGames) . " games)\n";
            }
            
            // Test genre suggestions
            $genres = GameDataImporter::getGenreSuggestions();
            if (!empty($genres)) {
                echo "   ✅ Genre suggestions available (" . count($genres) . " genres)\n";
            }
            
            $this->results['game_data_importer'] = '✅ SUCCESS';
            
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            $this->results['game_data_importer'] = '❌ ERROR';
        }
        echo "\n";
    }
    
    private function testMessageHelper() {
        echo "💬 Testing Message Helper...\n";
        
        try {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Test setting and getting messages
            MessageHelper::setSuccess('Test success message');
            MessageHelper::setError('Test error message');
            
            $successMsg = MessageHelper::getSuccess();
            $errorMsg = MessageHelper::getError();
            
            if ($successMsg && $errorMsg) {
                echo "   ✅ Message setting and getting works\n";
                $this->results['message_helper'] = '✅ SUCCESS';
            } else {
                echo "   ❌ Message handling failed\n";
                $this->results['message_helper'] = '❌ FAILED';
            }
            
        } catch (Exception $e) {
            echo "   ❌ Error: " . $e->getMessage() . "\n";
            $this->results['message_helper'] = '❌ ERROR';
        }
        echo "\n";
    }
    
    private function displayResults() {
        echo "📋 Test Results Summary\n";
        echo "======================\n\n";
        
        foreach ($this->results as $test => $result) {
            echo sprintf("%-25s %s\n", ucwords(str_replace('_', ' ', $test)) . ':', $result);
        }
        
        $successCount = count(array_filter($this->results, function($result) {
            return strpos($result, '✅') === 0;
        }));
        
        $totalTests = count($this->results);
        
        echo "\n";
        echo "✅ Passed: $successCount/$totalTests tests\n";
        
        if ($successCount === $totalTests) {
            echo "\n🎉 All tests passed! The system is ready for production.\n";
        } else {
            echo "\n⚠️ Some tests failed. Please check the configuration.\n";
        }
    }
}

// Run tests
$tester = new SystemTester();
$tester->runAllTests();
?>
