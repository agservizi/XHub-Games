<?php
/**
 * Form Validation Utility for Xbox Games Catalog
 * Handles validation for game creation and editing
 */

class FormValidator {
    
    private $errors = [];
    private $data = [];

    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * Validate game form data
     */
    public function validateGame() {
        $this->validateRequired('title', 'Il titolo è obbligatorio');
        $this->validateRequired('release_year', 'L\'anno di uscita è obbligatorio');
        $this->validateRequired('genre', 'Il genere è obbligatorio');
        $this->validateRequired('developer', 'Lo sviluppatore è obbligatorio');
        $this->validateRequired('publisher', 'Il publisher è obbligatorio');

        // Validate title length
        $this->validateLength('title', 1, 255, 'Il titolo deve essere tra 1 e 255 caratteri');

        // Validate year
        $this->validateYear('release_year');

        // Validate URL if provided
        if (!empty($this->data['cover_url'])) {
            $this->validateUrl('cover_url', 'L\'URL della copertina non è valido');
        }

        // Validate rating if provided
        if (!empty($this->data['personal_rating'])) {
            $this->validateRating('personal_rating');
        }

        // Validate enum fields
        $this->validateEnum('support_type', ['Fisico', 'Digitale', 'Entrambi'], 'Tipo di supporto non valido');
        $this->validateEnum('status', ['Da iniziare', 'In corso', 'Completato'], 'Status non valido');

        return empty($this->errors);
    }

    /**
     * Validate required field
     */
    private function validateRequired($field, $message = null) {
        if (empty($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = $message ?? "Il campo {$field} è obbligatorio";
        }
    }

    /**
     * Validate field length
     */
    private function validateLength($field, $min, $max, $message = null) {
        if (isset($this->data[$field])) {
            $length = strlen(trim($this->data[$field]));
            if ($length < $min || $length > $max) {
                $this->errors[$field] = $message ?? "Il campo {$field} deve essere tra {$min} e {$max} caratteri";
            }
        }
    }

    /**
     * Validate year
     */
    private function validateYear($field) {
        if (isset($this->data[$field])) {
            $year = (int)$this->data[$field];
            $currentYear = (int)date('Y');
            
            if ($year < 1970 || $year > $currentYear + 2) {
                $this->errors[$field] = "L'anno deve essere tra 1970 e " . ($currentYear + 2);
            }
        }
    }

    /**
     * Validate URL
     */
    private function validateUrl($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
                $this->errors[$field] = $message ?? "Il campo {$field} deve essere un URL valido";
            }
        }
    }

    /**
     * Validate rating (0-10)
     */
    private function validateRating($field) {
        if (isset($this->data[$field])) {
            $rating = floatval($this->data[$field]);
            if ($rating < 0 || $rating > 10) {
                $this->errors[$field] = "Il voto deve essere tra 0 e 10";
            }
        }
    }

    /**
     * Validate enum values
     */
    private function validateEnum($field, $allowedValues, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!in_array($this->data[$field], $allowedValues)) {
                $this->errors[$field] = $message ?? "Valore non valido per {$field}";
            }
        }
    }

    /**
     * Get validation errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Check if validation passed
     */
    public function isValid() {
        return empty($this->errors);
    }

    /**
     * Get first error for a field
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    /**
     * Sanitize data for database
     */
    public function getSanitizedData() {
        $sanitized = [];
        
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'title':
                case 'genre':
                case 'developer':
                case 'publisher':
                case 'language':
                    $sanitized[$key] = trim(strip_tags($value));
                    break;
                    
                case 'cover_url':
                    $sanitized[$key] = filter_var(trim($value), FILTER_SANITIZE_URL);
                    break;
                    
                case 'release_year':
                    $sanitized[$key] = (int)$value;
                    break;
                    
                case 'personal_rating':
                    $sanitized[$key] = !empty($value) ? round(floatval($value), 1) : null;
                    break;
                    
                case 'notes':
                    $sanitized[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
                    break;
                    
                case 'support_type':
                case 'status':
                    $sanitized[$key] = trim($value);
                    break;
                    
                default:
                    $sanitized[$key] = trim($value);
            }
        }
        
        return $sanitized;
    }

    /**
     * Display errors in HTML format
     */
    public function displayErrors() {
        if (empty($this->errors)) {
            return '';
        }

        $html = '<div class="bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded mb-4">';
        $html .= '<h4 class="font-bold">❌ Errori di validazione:</h4>';
        $html .= '<ul class="list-disc list-inside mt-2">';
        
        foreach ($this->errors as $field => $error) {
            $html .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        
        $html .= '</ul></div>';
        
        return $html;
    }

    /**
     * Display success message
     */
    public static function displaySuccess($message) {
        return '<div class="bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-4">
                    <h4 class="font-bold">✅ ' . htmlspecialchars($message) . '</h4>
                </div>';
    }
}
?>
