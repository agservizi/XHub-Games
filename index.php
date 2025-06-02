<?php
/**
 * Xbox Games Catalog - Entry Point
 * Modern gaming catalog with Xbox-inspired design
 */

session_start();

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the router
require_once __DIR__ . '/src/Router.php';

// Initialize and handle the request
$router = new Router();
$router->handleRequest();
?>
