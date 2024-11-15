<?php
require_once __DIR__ . '/../models/Carrinho.php';

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
       
        $path = ltrim($path, '/');
        
        $path = preg_replace('/\{(\w+)\}/', '(\d+)', $path);
        
        $this->routes[] = [
            'method' => $method, 
            'path' => "#^" . $path . "$#", 
            'callback' => $callback
        ];
    }

    public function dispatch($requestedPath) {
        $requestedMethod = $_SERVER["REQUEST_METHOD"];
        
        $requestedPath = ltrim($requestedPath, '/');
        
        error_log("Método requisitado: " . $requestedMethod);
        error_log("Caminho requisitado: " . $requestedPath);

        if (empty($requestedPath)) {
            
            return $this->handleDefaultRoute();
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestedMethod && preg_match($route['path'], $requestedPath, $matches)) {
                array_shift($matches);
                return call_user_func($route['callback'], ...array_values($matches));
            }
        }

        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
            'error' => 'Rota não encontrada',
            'path' => $requestedPath,
            'method' => $requestedMethod
        ]);
    }

    private function handleDefaultRoute() {
        header('Content-Type: application/json');

    }
}