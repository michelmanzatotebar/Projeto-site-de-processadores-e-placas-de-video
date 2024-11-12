<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $callback)
    {
        $path = preg_replace('/{(\w+)}/', '([^/]+)', $path);
        $this->routes[] = [
            'method' => $method,
            'path' => "#^" . $path . "$#",
            'callback' => $callback
        ];
    }

    public function dispatch($requestedPath)
    {
        $requestedMethod = $_SERVER["REQUEST_METHOD"];
        error_log("Requested Path: " . $requestedPath);
        error_log("Request Method: " . $requestedMethod);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestedMethod && preg_match($route['path'], $requestedPath, $matches)) {
                array_shift($matches);
                try {
                    $result = call_user_func($route['callback'], ...$matches);
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    return;
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                    return;
                }
            }
        }
        
        http_response_code(404);
        echo json_encode(['error' => '404 - Rota não encontrada']);
    }
}