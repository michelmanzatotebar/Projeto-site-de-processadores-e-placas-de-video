<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../src/models/Produto.php';
require_once __DIR__ . '/../src/models/Cliente.php';
require_once __DIR__ . '/../src/models/Carrinho.php';
require_once __DIR__ . '/../src/models/Pedido.php';

require_once __DIR__ . '/../src/controllers/CarrinhoController.php';
require_once __DIR__ . '/../src/controllers/ClienteController.php';
require_once __DIR__ . '/../src/controllers/PedidoController.php';
require_once __DIR__ . '/../src/controllers/ProdutoController.php';

require_once __DIR__ . '/../src/config/db.php';
require_once __DIR__ . '/../src/routes/Router.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$router = new Router();
$carrinhoController = new CarrinhoController($pdo);
$clienteController = new ClienteController($pdo);
$pedidoController = new PedidoController($pdo);
$produtoController = new ProdutoController($pdo);

$router->add('GET', '/Carrinho', [$carrinhoController, 'list']);
$router->add('GET', '/Carrinho/{id}', [$carrinhoController, 'getById']);
$router->add('POST', '/Carrinho', [$carrinhoController, 'create']);
$router->add('PUT', '/Carrinho/{id}', [$carrinhoController, 'update']);
$router->add('DELETE', '/Carrinho/{id}', [$carrinhoController, 'delete']);

$router->add('GET', '/Cliente', [$clienteController, 'list']);
$router->add('GET', '/Cliente/{id}', [$clienteController, 'getById']);
$router->add('POST', '/Cliente', [$clienteController, 'create']);
$router->add('PUT', '/Cliente/{id}', [$clienteController, 'update']);
$router->add('DELETE', '/Cliente/{id}', [$clienteController, 'delete']);

$router->add('POST', '/Pedido', [$pedidoController, 'create']);
$router->add('GET', '/Pedido', [$pedidoController, 'list']);
$router->add('GET', '/Pedido/{id}', [$pedidoController, 'getById']);
$router->add('PUT', '/Pedido/{id}', [$pedidoController, 'update']);
$router->add('DELETE', '/Pedido/{id}', [$pedidoController, 'delete']);

$router->add('GET', '/Produto', [$produtoController, 'list']);
$router->add('GET', '/Produto/{id}', [$produtoController, 'getById']);
$router->add('POST', '/Produto', [$produtoController, 'create']);
$router->add('PUT', '/Produto/{id}', [$produtoController, 'update']);
$router->add('DELETE', '/Produto/{id}', [$produtoController, 'delete']);

$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$basePath = '/Projeto-site-de-processadores-e-placas-de-video/public';
$requestedPath = str_replace($basePath, '', $requestedPath);
$requestedPath = trim($requestedPath, '/');

error_log("Método da requisição: " . $_SERVER['REQUEST_METHOD']);
error_log("Caminho requisitado: " . $requestedPath);

$router->dispatch($requestedPath);