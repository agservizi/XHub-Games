<?php
/**
 * Games Controller for Xbox Games Catalog
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Game.php';
require_once __DIR__ . '/../utils/FormValidator.php';
require_once __DIR__ . '/../utils/ImageUtils.php';

class GameController {
    private $game;

    public function __construct() {
        $this->game = new Game();
    }

    /**
     * Display all games with filters
     */
    public function index() {
        $filters = [];
        
        if (isset($_GET['genre']) && !empty($_GET['genre'])) {
            $filters['genre'] = $_GET['genre'];
        }
        
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        if (isset($_GET['support_type']) && !empty($_GET['support_type'])) {
            $filters['support_type'] = $_GET['support_type'];
        }
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        $games = $this->game->getAll($filters);
        $genres = $this->game->getGenres();
        $stats = $this->game->getStats();
        
        include __DIR__ . '/../views/games/index.php';
    }

    /**
     * Show single game
     */
    public function show($id) {
        $game = $this->game->getById($id);
        
        if (!$game) {
            header("Location: /");
            exit();
        }
        
        include __DIR__ . '/../views/games/show.php';
    }    /**
     * Show create form
     */
    public function create() {
        // Get validation errors and form data from session if available
        $errors = $_SESSION['validation_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? [];
        
        // Clear session data
        unset($_SESSION['validation_errors'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/games/create.php';
    }

    /**
     * Store new game
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create validator instance
            $validator = new FormValidator($_POST);
            
            if ($validator->validateGame()) {
                // Get sanitized data
                $data = $validator->getSanitizedData();
                
                if ($this->game->create($data)) {
                    $_SESSION['success'] = 'Gioco aggiunto con successo!';
                    header("Location: /");
                    exit;
                } else {
                    $_SESSION['error'] = "Errore durante l'aggiunta del gioco";
                    header("Location: /create");
                    exit;
                }
            } else {
                $_SESSION['validation_errors'] = $validator->getErrors();
                $_SESSION['form_data'] = $_POST;
                header("Location: /create");
                exit;
            }
        }
    }    /**
     * Show edit form
     */
    public function edit($id) {
        $game = $this->game->getById($id);
        
        if (!$game) {
            header("Location: /");
            exit();
        }
        
        // Get validation errors and form data from session if available
        $errors = $_SESSION['validation_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? $game;
        
        // Clear session data
        unset($_SESSION['validation_errors'], $_SESSION['form_data']);
        
        include __DIR__ . '/../views/games/edit.php';
    }

    /**
     * Update game
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create validator instance
            $validator = new FormValidator($_POST);
            
            if ($validator->validateGame()) {
                // Get sanitized data
                $data = $validator->getSanitizedData();
                
                if ($this->game->update($id, $data)) {
                    $_SESSION['success'] = 'Gioco aggiornato con successo!';
                    header("Location: /");
                    exit;
                } else {
                    $_SESSION['error'] = "Errore durante l'aggiornamento del gioco";
                    header("Location: /edit/$id");
                    exit;
                }
            } else {
                $_SESSION['validation_errors'] = $validator->getErrors();
                $_SESSION['form_data'] = $_POST;
                header("Location: /edit/$id");
                exit;
            }
        }
    }

    /**
     * Delete game
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->game->delete($id)) {
                $_SESSION['success'] = 'Gioco eliminato con successo!';
            } else {
                $_SESSION['error'] = 'Errore durante l\'eliminazione del gioco.';
            }
        }
        
        header("Location: /");
        exit();
    }

    /**
     * API endpoint for games data
     */
    public function api() {
        header('Content-Type: application/json');
        
        $filters = [];
        if (isset($_GET['genre'])) $filters['genre'] = $_GET['genre'];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        if (isset($_GET['support_type'])) $filters['support_type'] = $_GET['support_type'];
        if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
        
        $games = $this->game->getAll($filters);
        echo json_encode($games);
        exit();
    }

    /**
     * Esporta giochi in CSV
     */
    public function exportCsv() {
        $games = $this->game->getAll();
        $file = \GameDataImporter::exportToCSV($games);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="xbox_games_export.csv"');
        readfile($file);
        exit;
    }

    /**
     * Importa giochi da CSV
     */
    public function importCsv() {
        if (!empty($_FILES['csv_file']['tmp_name'])) {
            $rows = \GameDataImporter::importFromCSV($_FILES['csv_file']['tmp_name']);
            foreach ($rows as $row) {
                $this->game->create($row);
            }
            $_SESSION['success'] = 'Importazione completata!';
        } else {
            $_SESSION['error'] = 'Nessun file selezionato.';
        }
        header('Location: /');
        exit;
    }
}
?>
