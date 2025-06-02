<?php
/**
 * API Endpoint for Xbox Games Catalog
 * Handles AJAX requests for auto-completion and suggestions
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/src/utils/GameDataImporter.php';
require_once __DIR__ . '/src/models/Game.php';

// Set JSON content type
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle different API actions
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'suggest_game':
        handleGameSuggestions();
        break;
        
    case 'get_developers':
        echo json_encode(GameDataImporter::getPopularDevelopers());
        break;
        
    case 'get_publishers':
        echo json_encode(GameDataImporter::getPopularPublishers());
        break;
        
    case 'get_genres':
        echo json_encode(GameDataImporter::getPopularGenres());
        break;
        
    case 'complete_game':
        handleGameCompletion();
        break;
        
    case 'validate_cover':
        handleCoverValidation();
        break;
        
    case 'search_games':
        handleGameSearch();
        break;
        
    case 'get_stats':
        handleStatsRequest();
        break;
        
    case 'search_external':
        $q = $_GET['q'] ?? '';
        echo json_encode(GameDataImporter::searchExternalGames($q));
        break;
        
    case 'export_csv':
        $games = (new Game())->getAll();
        $file = GameDataImporter::exportToCSV($games);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="xbox_games_export.csv"');
        readfile($file);
        exit;
        
    case 'import_csv':
        // Da implementare lato controller per sicurezza (POST file upload)
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        http_response_code(400);
}

function handleGameSuggestions() {
    $title = $_GET['title'] ?? '';
    if (strlen($title) < 2) {
        echo json_encode([]);
        return;
    }
    
    $suggestions = GameDataImporter::getGameSuggestions($title);
    echo json_encode($suggestions);
}

function handleGameCompletion() {
    $title = $_GET['title'] ?? '';
    $data = GameDataImporter::getCompleteGameData($title);
    
    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gioco non trovato nel database']);
    }
}

function handleCoverValidation() {
    $url = $_GET['url'] ?? '';
    
    if (empty($url)) {
        echo json_encode(['valid' => false, 'message' => 'URL vuoto']);
        return;
    }
    
    // Basic URL validation
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        echo json_encode(['valid' => false, 'message' => 'URL non valido']);
        return;
    }
    
    // Check if it's an image URL
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    
    if (!in_array($extension, $imageExtensions) && 
        !strpos($url, 'igdb.com') && 
        !strpos($url, 'steamcdn')) {
        echo json_encode(['valid' => false, 'message' => 'Non sembra essere un\'immagine']);
        return;
    }
    
    echo json_encode(['valid' => true, 'message' => 'URL valido']);
}

function handleGameSearch() {
    $query = $_GET['q'] ?? '';
    
    if (strlen($query) < 2) {
        echo json_encode([]);
        return;
    }
    
    try {
        $game = new Game();
        $results = $game->search($query, 10); // Limit to 10 results
        
        $formatted = array_map(function($game) {
            return [
                'id' => $game['id'],
                'title' => $game['title'],
                'genre' => $game['genre'],
                'year' => $game['release_year'],
                'cover' => $game['cover_url']
            ];
        }, $results);
        
        echo json_encode($formatted);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Errore nella ricerca: ' . $e->getMessage()]);
        http_response_code(500);
    }
}

function handleStatsRequest() {
    try {
        $game = new Game();
        $stats = $game->getStats();
        
        // Add more detailed stats
        $detailedStats = [
            'total_games' => $stats['total_games'],
            'completed_games' => $stats['completed_games'],
            'in_progress_games' => $stats['in_progress_games'],
            'not_started_games' => $stats['total_games'] - $stats['completed_games'] - $stats['in_progress_games'],
            'average_rating' => round($stats['average_rating'], 1),
            'completion_percentage' => $stats['total_games'] > 0 ? 
                round(($stats['completed_games'] / $stats['total_games']) * 100, 1) : 0,
            'top_genres' => $game->getTopGenres(5),
            'recent_additions' => $game->getRecentGames(5)
        ];
        
        echo json_encode($detailedStats);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Errore nel recupero statistiche: ' . $e->getMessage()]);
        http_response_code(500);
    }
}
?>
