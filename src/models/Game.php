<?php
/**
 * Game Model for Xbox Games Catalog
 */

require_once dirname(__DIR__, 2) . '/config/database.php';

class Game {
    private $conn;
    private $table_name = "games";

    // Game properties
    public $id;
    public $title;
    public $cover_url;
    public $release_year;
    public $genre;
    public $developer;
    public $publisher;
    public $language;
    public $support_type;
    public $status;
    public $personal_rating;
    public $notes;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get all games with optional filters
     */
    public function getAll($filters = []) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params = [];

        if (!empty($filters['genre'])) {
            $query .= " AND genre = :genre";
            $params['genre'] = $filters['genre'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['support_type'])) {
            $query .= " AND support_type = :support_type";
            $params['support_type'] = $filters['support_type'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (title LIKE :search OR developer LIKE :search OR publisher LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY title ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get game by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new game
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (title, cover_url, release_year, genre, developer, publisher, language, support_type, status, personal_rating, notes) 
                  VALUES (:title, :cover_url, :release_year, :genre, :developer, :publisher, :language, :support_type, :status, :personal_rating, :notes)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':cover_url', $data['cover_url']);
        $stmt->bindParam(':release_year', $data['release_year']);
        $stmt->bindParam(':genre', $data['genre']);
        $stmt->bindParam(':developer', $data['developer']);
        $stmt->bindParam(':publisher', $data['publisher']);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':support_type', $data['support_type']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':personal_rating', $data['personal_rating']);
        $stmt->bindParam(':notes', $data['notes']);
        
        return $stmt->execute();
    }

    /**
     * Update game
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title, cover_url = :cover_url, release_year = :release_year, 
                      genre = :genre, developer = :developer, publisher = :publisher, 
                      language = :language, support_type = :support_type, status = :status, 
                      personal_rating = :personal_rating, notes = :notes 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':cover_url', $data['cover_url']);
        $stmt->bindParam(':release_year', $data['release_year']);
        $stmt->bindParam(':genre', $data['genre']);
        $stmt->bindParam(':developer', $data['developer']);
        $stmt->bindParam(':publisher', $data['publisher']);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':support_type', $data['support_type']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':personal_rating', $data['personal_rating']);
        $stmt->bindParam(':notes', $data['notes']);
        
        return $stmt->execute();
    }

    /**
     * Delete game
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get distinct genres
     */
    public function getGenres() {
        $query = "SELECT DISTINCT genre FROM " . $this->table_name . " ORDER BY genre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get game statistics
     */
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_games,
                    SUM(CASE WHEN status = 'Completato' THEN 1 ELSE 0 END) as completed_games,
                    SUM(CASE WHEN status = 'In corso' THEN 1 ELSE 0 END) as in_progress_games,
                    SUM(CASE WHEN status = 'Da iniziare' THEN 1 ELSE 0 END) as not_started_games,
                    AVG(personal_rating) as average_rating
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>
