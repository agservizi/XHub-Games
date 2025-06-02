<?php
/**
 * Game Data Import Utility for Xbox Games Catalog
 * Provides suggestions and auto-completion for game data
 */

class GameDataImporter {
    
    // Popular Xbox games data for suggestions
    private static $popularGames = [
        'Halo Infinite' => [
            'genre' => 'Sparatutto',
            'developer' => '343 Industries',
            'publisher' => 'Microsoft Studios',
            'release_year' => 2021
        ],
        'Forza Horizon 5' => [
            'genre' => 'Racing',
            'developer' => 'Playground Games',
            'publisher' => 'Microsoft Studios',
            'release_year' => 2021
        ],
        'Gears 5' => [
            'genre' => 'Azione',
            'developer' => 'The Coalition',
            'publisher' => 'Microsoft Studios',
            'release_year' => 2019
        ],
        'Sea of Thieves' => [
            'genre' => 'Avventura',
            'developer' => 'Rare Ltd.',
            'publisher' => 'Microsoft Studios',
            'release_year' => 2018
        ],
        'Cyberpunk 2077' => [
            'genre' => 'RPG',
            'developer' => 'CD Projekt RED',
            'publisher' => 'CD Projekt',
            'release_year' => 2020
        ]
    ];

    /**
     * Get game suggestions based on title
     */
    public static function getGameSuggestions($title) {
        $suggestions = [];
        $title = strtolower($title);
        
        foreach (self::$popularGames as $gameTitle => $data) {
            if (stripos($gameTitle, $title) !== false || 
                stripos($title, strtolower($gameTitle)) !== false) {
                $suggestions[] = array_merge(['title' => $gameTitle], $data);
            }
        }
        
        return $suggestions;
    }

    /**
     * Generate complete game data from title
     */
    public static function getCompleteGameData($title) {
        foreach (self::$popularGames as $gameTitle => $data) {
            if (strcasecmp($gameTitle, $title) === 0) {
                return array_merge(['title' => $gameTitle], $data);
            }
        }
        
        return null;
    }

    /**
     * Get popular developers list
     */
    public static function getPopularDevelopers() {
        return [
            '343 Industries', 'Playground Games', 'The Coalition', 'Rare Ltd.',
            'CD Projekt RED', 'Ubisoft Montreal', 'Bethesda Game Studios',
            'id Software', 'Machine Games', 'Arkane Studios', 'Obsidian Entertainment',
            'inXile Entertainment', 'Ninja Theory', 'Compulsion Games'
        ];
    }

    /**
     * Get popular publishers list
     */
    public static function getPopularPublishers() {
        return [
            'Microsoft Studios', 'Xbox Game Studios', 'CD Projekt', 'Ubisoft',
            'Electronic Arts', 'Activision', 'Bethesda Softworks', 'Square Enix',
            '2K Games', 'Warner Bros. Games', 'Bandai Namco', 'Capcom'
        ];
    }

    /**
     * Get popular genres for Xbox
     */
    public static function getPopularGenres() {
        return [
            'Azione', 'Avventura', 'RPG', 'Sparatutto', 'Racing', 'Sport',
            'Simulazione', 'Strategia', 'Puzzle', 'Indie', 'Horror',
            'Piattaforme', 'Fighting', 'Rhythm', 'Azione/RPG', 'Stealth'
        ];
    }

    /**
     * Generate AJAX endpoint for game suggestions
     */
    public static function handleAjaxRequest() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['action'])) {
            echo json_encode(['error' => 'Action not specified']);
            return;
        }

        switch ($_GET['action']) {
            case 'suggest_game':
                $title = $_GET['title'] ?? '';
                $suggestions = self::getGameSuggestions($title);
                echo json_encode($suggestions);
                break;
                
            case 'get_developers':
                echo json_encode(self::getPopularDevelopers());
                break;
                
            case 'get_publishers':
                echo json_encode(self::getPopularPublishers());
                break;
                
            case 'get_genres':
                echo json_encode(self::getPopularGenres());
                break;
                
            case 'complete_game':
                $title = $_GET['title'] ?? '';
                $data = self::getCompleteGameData($title);
                echo json_encode($data ?: ['error' => 'Game not found']);
                break;
                
            default:
                echo json_encode(['error' => 'Invalid action']);
        }
    }

    /**
     * Generate cover URL suggestions based on title
     */
    public static function suggestCoverUrls($title) {
        $suggestions = [];
        $cleanTitle = self::cleanTitleForSearch($title);
        
        // Common cover image sources
        $sources = [
            "https://images.igdb.com/igdb/image/upload/t_cover_big/{$cleanTitle}.jpg",
            "https://steamcdn-a.akamaihd.net/steam/apps/{$cleanTitle}/header.jpg",
            "https://store-images.s-microsoft.com/image/apps.{$cleanTitle}.jpg"
        ];
        
        return $sources;
    }

    /**
     * Clean title for search purposes
     */
    private static function cleanTitleForSearch($title) {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $title));
    }

    /**
     * Import from CSV file
     */
    public static function importFromCSV($filePath) {
        if (!file_exists($filePath)) {
            return ['error' => 'File not found'];
        }

        $imported = [];
        $errors = [];
        
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle); // Skip header row
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                try {
                    $gameData = [
                        'title' => $data[0] ?? '',
                        'genre' => $data[1] ?? '',
                        'developer' => $data[2] ?? '',
                        'publisher' => $data[3] ?? '',
                        'release_year' => (int)($data[4] ?? date('Y')),
                        'cover_url' => $data[5] ?? '',
                        'language' => $data[6] ?? 'Italiano',
                        'support_type' => $data[7] ?? 'Digitale',
                        'status' => $data[8] ?? 'Da iniziare',
                        'personal_rating' => !empty($data[9]) ? (float)$data[9] : null,
                        'notes' => $data[10] ?? ''
                    ];
                    
                    // Validate required fields
                    if (empty($gameData['title']) || empty($gameData['genre']) || empty($gameData['developer'])) {
                        $errors[] = "Riga con dati mancanti: " . implode(', ', $data);
                        continue;
                    }
                    
                    $imported[] = $gameData;
                    
                } catch (Exception $e) {
                    $errors[] = "Errore nella riga: " . $e->getMessage();
                }
            }
            
            fclose($handle);
        }
        
        return [
            'imported' => $imported,
            'errors' => $errors,
            'count' => count($imported)
        ];
    }

    /**
     * Export games to CSV
     */
    public static function exportToCSV($games, $filename = 'xbox_games_export.csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV Header
        fputcsv($output, [
            'Titolo', 'Genere', 'Sviluppatore', 'Publisher', 'Anno',
            'Copertina', 'Lingua', 'Supporto', 'Status', 'Voto', 'Note'
        ]);
        
        // Data rows
        foreach ($games as $game) {
            fputcsv($output, [
                $game['title'],
                $game['genre'],
                $game['developer'],
                $game['publisher'],
                $game['release_year'],
                $game['cover_url'],
                $game['language'],
                $game['support_type'],
                $game['status'],
                $game['personal_rating'],
                $game['notes']
            ]);
        }
        
        fclose($output);
    }
}
?>
