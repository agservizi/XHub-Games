<?php
/**
 * Xbox Games Catalog - Database Migration Script
 * This script automatically creates the database and imports sample data
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'xbox_games_catalog';

try {
    // Connect to MySQL server (without database)
    echo "🔌 Connessione al server MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connesso al server MySQL\n";

    // Create database if it doesn't exist
    echo "🏗️ Creazione database '$database'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$database' creato o già esistente\n";

    // Connect to the specific database
    echo "🔗 Connessione al database '$database'...\n";
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connesso al database '$database'\n";

    // Read and execute SQL file
    echo "📁 Lettura file init.sql...\n";
    $sql = file_get_contents(__DIR__ . '/database/init.sql');
    
    if ($sql === false) {
        throw new Exception("Impossibile leggere il file init.sql");
    }

    // Split queries and execute them
    echo "⚡ Esecuzione query SQL...\n";
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($queries as $query) {
        if (!empty($query) && !str_starts_with($query, '--')) {
            try {
                $pdo->exec($query);
                if (str_contains($query, 'CREATE TABLE')) {
                    echo "✅ Tabella creata\n";
                } elseif (str_contains($query, 'INSERT INTO')) {
                    echo "✅ Dati inseriti\n";
                }
            } catch (PDOException $e) {
                if ($e->getCode() != '42S01') { // Table already exists
                    echo "⚠️ Avviso: " . $e->getMessage() . "\n";
                }
            }
        }
    }

    // Check results
    echo "📊 Verifica dati...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM games");
    $count = $stmt->fetch()['count'];
    echo "✅ Trovati $count giochi nel database\n";

    echo "\n🎉 MIGRAZIONE COMPLETATA CON SUCCESSO!\n";
    echo "🌐 Apri http://localhost:8000 per utilizzare l'applicazione\n";

} catch (Exception $e) {
    echo "\n❌ ERRORE: " . $e->getMessage() . "\n";
    echo "\n🔧 Possibili soluzioni:\n";
    echo "   - Verifica che MySQL sia in esecuzione\n";
    echo "   - Controlla username e password\n";
    echo "   - Assicurati di avere i permessi per creare database\n";
    exit(1);
}
?>
