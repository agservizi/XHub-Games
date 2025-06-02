<?php
/**
 * Simple Router for Xbox Games Catalog
 */

require_once __DIR__ . '/controllers/GameController.php';

class Router {
    private $routes = [];
    private $gameController;

    public function __construct() {
        $this->gameController = new GameController();
        $this->setupRoutes();
    }

    private function setupRoutes() {
        $this->routes = [
            'GET /' => [$this->gameController, 'index'],
            'GET /games' => [$this->gameController, 'index'],
            'GET /game/(\d+)' => [$this->gameController, 'show'],
            'GET /nuovo-gioco' => [$this->gameController, 'create'],
            'POST /store' => [$this->gameController, 'store'],
            'GET /edit/(\d+)' => [$this->gameController, 'edit'],
            'POST /update/(\d+)' => [$this->gameController, 'update'],
            'POST /delete/(\d+)' => [$this->gameController, 'destroy'],
            'GET /api/games' => [$this->gameController, 'api']
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';

        foreach ($this->routes as $route => $handler) {
            list($routeMethod, $routePath) = explode(' ', $route, 2);
            
            if ($method === $routeMethod) {
                $pattern = '#^' . $routePath . '$#';
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove full match
                    
                    if (is_array($handler)) {
                        list($controller, $action) = $handler;
                        if (!empty($matches)) {
                            call_user_func_array([$controller, $action], $matches);
                        } else {
                            call_user_func([$controller, $action]);
                        }
                    }
                    return;
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        include __DIR__ . '/views/404.php';
    }
}
?>
