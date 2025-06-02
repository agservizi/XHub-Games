<?php
/**
 * Message Helper for Xbox Games Catalog
 * Handles session-based success and error messages
 */

class MessageHelper {
    
    /**
     * Display success message from session
     */
    public static function displaySuccess() {
        if (isset($_SESSION['success'])) {
            $message = $_SESSION['success'];
            unset($_SESSION['success']);
            
            return '<div class="bg-green-900 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 flash-message">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>' . htmlspecialchars($message) . '</span>
                        </div>
                    </div>';
        }
        return '';
    }
    
    /**
     * Display error message from session
     */
    public static function displayError() {
        if (isset($_SESSION['error'])) {
            $message = $_SESSION['error'];
            unset($_SESSION['error']);
            
            return '<div class="bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded mb-6 flash-message">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>' . htmlspecialchars($message) . '</span>
                        </div>
                    </div>';
        }
        return '';
    }
    
    /**
     * Display all flash messages
     */
    public static function displayAll() {
        return self::displaySuccess() . self::displayError();
    }
    
    /**
     * Set success message
     */
    public static function setSuccess($message) {
        $_SESSION['success'] = $message;
    }
    
    /**
     * Set error message
     */
    public static function setError($message) {
        $_SESSION['error'] = $message;
    }
    
    /**
     * Check if there are any messages
     */
    public static function hasMessages() {
        return isset($_SESSION['success']) || isset($_SESSION['error']);
    }
}
?>
