<?php
/**
 * Xbox Games Catalog - Setup Utility
 * Use this file to test database connection and import initial data
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>\n";
echo "<html lang='it'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Xbox Games Catalog - Setup</title>\n";
echo "    <script src='https://cdn.tailwindcss.com'></script>\n";
echo "    <style>\n";
echo "        body { background: linear-gradient(135deg, #181A1B 0%, #1F1F1F 100%); }\n";
echo "        .xbox-green { color: #107C10; }\n";
echo "        .xbox-border { border-color: #107C10; }\n";
echo "        .xbox-bg { background-color: #107C10; }\n";
echo "        .glow { box-shadow: 0 0 20px rgba(16, 124, 16, 0.5); }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body class='min-h-screen text-white p-8'>\n";
echo "    <div class='max-w-4xl mx-auto'>\n";
echo "        <h1 class='text-4xl font-bold text-center mb-8 xbox-green'>🎮 Xbox Games Catalog - Setup</h1>\n";

// Test database connection
echo "        <div class='bg-gray-800 rounded-lg p-6 mb-6 border xbox-border'>\n";
echo "            <h2 class='text-2xl font-bold mb-4 xbox-green'>🔌 Test Connessione Database</h2>\n";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "            <div class='bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-4'>\n";
        echo "                ✅ <strong>Connessione riuscita!</strong> Database collegato correttamente.\n";
        echo "            </div>\n";
        
        // Check if tables exist
        $stmt = $conn->query("SHOW TABLES LIKE 'games'");
        $tableExists = $stmt->rowCount() > 0;
        
        if ($tableExists) {
            echo "            <div class='bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-4'>\n";
            echo "                ✅ <strong>Tabella 'games' trovata!</strong>\n";
            echo "            </div>\n";
            
            // Count games
            $stmt = $conn->query("SELECT COUNT(*) as count FROM games");
            $count = $stmt->fetch()['count'];
            
            echo "            <div class='bg-blue-900 border border-blue-600 text-blue-200 px-4 py-3 rounded mb-4'>\n";
            echo "                📊 <strong>Giochi nel database:</strong> {$count}\n";
            echo "            </div>\n";
            
            if ($count == 0) {
                echo "            <div class='bg-yellow-900 border border-yellow-600 text-yellow-200 px-4 py-3 rounded mb-4'>\n";
                echo "                ⚠️ <strong>Nessun gioco trovato.</strong> Importa i dati di esempio con il pulsante qui sotto.\n";
                echo "            </div>\n";
                
                // Import button
                if (isset($_POST['import_data'])) {
                    $sql = file_get_contents('database/init.sql');
                    // Split queries and execute them
                    $queries = explode(';', $sql);
                    $imported = 0;
                    
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query) && strpos($query, 'INSERT INTO games') !== false) {
                            try {
                                $conn->exec($query);
                                $imported++;
                            } catch (PDOException $e) {
                                // Skip if already exists
                            }
                        }
                    }
                    
                    if ($imported > 0) {
                        echo "            <div class='bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-4'>\n";
                        echo "                ✅ <strong>Dati importati con successo!</strong> Aggiunti giochi di esempio.\n";
                        echo "            </div>\n";
                        echo "            <script>setTimeout(() => window.location.reload(), 2000);</script>\n";
                    }
                }
                
                echo "            <form method='POST' class='mb-4'>\n";
                echo "                <button type='submit' name='import_data' class='xbox-bg text-white px-6 py-3 rounded-lg glow hover:bg-green-600 transition-all duration-300'>\n";
                echo "                    📦 Importa Dati di Esempio\n";
                echo "                </button>\n";
                echo "            </form>\n";
            }
            
        } else {
            echo "            <div class='bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded mb-4'>\n";
            echo "                ❌ <strong>Tabella 'games' non trovata!</strong>\n";
            echo "            </div>\n";
            
            // Create table button
            if (isset($_POST['create_table'])) {
                $sql = file_get_contents('database/init.sql');
                // Extract CREATE TABLE query
                $createTableQuery = '';
                $lines = explode("\n", $sql);
                $inCreateTable = false;
                
                foreach ($lines as $line) {
                    if (strpos($line, 'CREATE TABLE') !== false) {
                        $inCreateTable = true;
                    }
                    
                    if ($inCreateTable) {
                        $createTableQuery .= $line . "\n";
                        if (strpos($line, ');') !== false) {
                            break;
                        }
                    }
                }
                
                try {
                    $conn->exec($createTableQuery);
                    echo "            <div class='bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-4'>\n";
                    echo "                ✅ <strong>Tabella creata con successo!</strong>\n";
                    echo "            </div>\n";
                    echo "            <script>setTimeout(() => window.location.reload(), 2000);</script>\n";
                } catch (PDOException $e) {
                    echo "            <div class='bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded mb-4'>\n";
                    echo "                ❌ <strong>Errore nella creazione della tabella:</strong> " . $e->getMessage() . "\n";
                    echo "            </div>\n";
                }
            }
            
            echo "            <form method='POST' class='mb-4'>\n";
            echo "                <button type='submit' name='create_table' class='xbox-bg text-white px-6 py-3 rounded-lg glow hover:bg-green-600 transition-all duration-300'>\n";
            echo "                    🛠️ Crea Tabella\n";
            echo "                </button>\n";
            echo "            </form>\n";
        }
        
    } else {
        echo "            <div class='bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded'>\n";
        echo "                ❌ <strong>Errore di connessione!</strong> Verifica le credenziali del database.\n";
        echo "            </div>\n";
    }
    
} catch (PDOException $e) {
    echo "            <div class='bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded'>\n";
    echo "                ❌ <strong>Errore di connessione:</strong> " . $e->getMessage() . "\n";
    echo "            </div>\n";
    echo "            <div class='mt-4 text-gray-300'>\n";
    echo "                <p><strong>Possibili soluzioni:</strong></p>\n";
    echo "                <ul class='list-disc list-inside mt-2 space-y-1'>\n";
    echo "                    <li>Verifica che MySQL sia in esecuzione</li>\n";
    echo "                    <li>Controlla le credenziali in <code>config/database.php</code></li>\n";
    echo "                    <li>Crea il database 'xbox_games_catalog' se non esiste</li>\n";
    echo "                </ul>\n";
    echo "            </div>\n";
}

echo "        </div>\n";

// Configuration info
$dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbName = $_ENV['DB_NAME'] ?? 'u427445037_xhub';
$dbUser = $_ENV['DB_USER'] ?? 'u427445037_xhub';

echo "        <div class='bg-gray-800 rounded-lg p-6 mb-6 border xbox-border'>\n";
echo "            <h2 class='text-2xl font-bold mb-4 xbox-green'>⚙️ Configurazione Database</h2>\n";
echo "            <div class='space-y-3 text-gray-300'>\n";
echo "                <p><strong>Database Host:</strong> {$dbHost}</p>\n";
echo "                <p><strong>Database Name:</strong> {$dbName}</p>\n";
echo "                <p><strong>Username:</strong> {$dbUser}</p>\n";
echo "                <p><strong>Password:</strong> [NASCOSTA]</p>\n";
echo "                <p><strong>Porta:</strong> 3306</p>\n";
echo "            </div>\n";
echo "            <div class='mt-4 p-4 bg-blue-900 border border-blue-600 rounded text-sm text-blue-200'>\n";
echo "                <p><strong>📋 Configurazione caricata da .env</strong></p>\n";
echo "                <p>Le credenziali vengono caricate automaticamente dal file <code>.env</code> nella root del progetto.</p>\n";
echo "            </div>\n";
echo "        </div>\n";

// Quick links
echo "        <div class='bg-gray-800 rounded-lg p-6 border xbox-border'>\n";
echo "            <h2 class='text-2xl font-bold mb-4 xbox-green'>🚀 Link Rapidi</h2>\n";
echo "            <div class='grid grid-cols-1 md:grid-cols-2 gap-4'>\n";
echo "                <a href='/' class='block bg-gray-700 hover:bg-gray-600 p-4 rounded-lg transition-colors'>\n";
echo "                    <div class='text-lg font-semibold xbox-green'>🎮 Vai all'App</div>\n";
echo "                    <div class='text-sm text-gray-400'>Accedi al catalogo giochi</div>\n";
echo "                </a>\n";
echo "                <a href='/create' class='block bg-gray-700 hover:bg-gray-600 p-4 rounded-lg transition-colors'>\n";
echo "                    <div class='text-lg font-semibold xbox-green'>➕ Aggiungi Gioco</div>\n";
echo "                    <div class='text-sm text-gray-400'>Inserisci un nuovo gioco</div>\n";
echo "                </a>\n";
echo "            </div>\n";
echo "        </div>\n";

echo "    </div>\n";
echo "</body>\n";
echo "</html>\n";
?>
