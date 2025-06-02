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
     * Cerca giochi tramite API esterna (mock RAWG/IGDB)
     */
    public static function searchExternalGames($query) {
        // Qui puoi integrare una vera API (RAWG, IGDB, ecc.)
        // MOCK: restituisce giochi fittizi
        $results = [
            [
                'title' => 'Halo Infinite',
                'release_year' => 2021,
                'genre' => 'Sparatutto',
                'developer' => '343 Industries',
                'publisher' => 'Microsoft Studios',
                'cover_url' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1r7b.jpg'
            ],
            [
                'title' => 'Forza Horizon 5',
                'release_year' => 2021,
                'genre' => 'Racing',
                'developer' => 'Playground Games',
                'publisher' => 'Microsoft Studios',
                'cover_url' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1tmu.jpg'
            ]
        ];
        // Filtro semplice
        return array_filter($results, function($g) use ($query) {
            return stripos($g['title'], $query) !== false;
        });
    }

    /**
     * Suggerisci generi
     */
    public static function getPopularGenres() {
        return [
            'Azione', 'Avventura', 'RPG', 'Sparatutto', 'Racing', 'Sport',
            'Strategia', 'Simulazione', 'Platform', 'Puzzle', 'Horror', 'Fighting'
        ];
    }

    /**
     * Suggerisci sviluppatori
     */
    public static function getPopularDevelopers() {
        return [
            '343 Industries', 'Playground Games', 'The Coalition', 'Rare Ltd.', 'CD Projekt RED', 'Ubisoft', 'EA', 'Rockstar Games'
        ];
    }

    /**
     * Suggerisci publisher
     */
    public static function getPopularPublishers() {
        return [
            'Microsoft Studios', 'CD Projekt', 'Ubisoft', 'EA', 'Rockstar Games', 'Bandai Namco', 'Square Enix'
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
     * Esporta giochi in CSV
     */
    public static function exportToCSV($games, $filename = 'xbox_games_export.csv') {
        $f = fopen($filename, 'w');
        fputcsv($f, array_keys($games[0]));
        foreach ($games as $game) {
            fputcsv($f, $game);
        }
        fclose($f);
        return $filename;
    }

    /**
     * Importa giochi da CSV
     */
    public static function importFromCSV($filePath) {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = array_combine($header, $data);
            }
            fclose($handle);
        }
        return $rows;
    }
}
?>
