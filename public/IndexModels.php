<?php
require_once '../config/db.php';
require_once '../controllers/usuarioController.php';
require_once '../Router.php';

$router = new Router();
$carrinhoController = new CarrinhoController($pdo);
$pedidoController = new PedidoController($pdo);
$produtoController = new ProdutoController($pdo);
$clienteController = new ClienteController($pdo);

header("Content-type: application/json; charset=UTF-8");

$router->add('GET','/Pedido', [$pedidoController, 'list']);
$router->add('GET','/Pedido/{id}', [$pedidoController, 'getById']);
$router->add('REMOVE','/Pedido/{id}', [$pedidoController, 'delete']);
$router->add('POST','/Pedido', [$pedidoController, 'getById']);
$router->add('PUT','/Pedido', [$pedidoController, 'getById']);

$router->add('GET','/Produto', [$produtoController, 'list']);
$router->add('GET','/Produto/{id}', [$produtoController, 'getById']);
$router->add('REMOVE','/Produto/{id}', [$produtoController, 'delete']);
$router->add('POST','/Produto', [$produtoController, 'getById']);
$router->add('PUT','/Produto', [$produtoController, 'getById']);

$router->add('GET','/Carrinho', [$carrinhoController, 'list']);
$router->add('GET','/Carrinho/{id}', [$carrinhoController, 'getById']);
$router->add('REMOVE','/Carrinho/{id}', [$carrinhoController, 'delete']);
$router->add('POST','/Carrinho', [$carrinhoController, 'getById']);
$router->add('PUT','/Carrinho', [$carrinhoController, 'getById']);


$router->add('GET','/Cliente', [$clienteController, 'list']);
$router->add('GET','/Cliente/{id}', [$clienteController, 'getById']);
$router->add('REMOVE','/Cliente/{id}', [$clienteController, 'delete']);
$router->add('POST','/Cliente', [$clienteController, 'getById']);
$router->add('PUT','/Cliente', [$clienteController, 'getById']);

$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathItems = explode("/", $requestedPath);
$requestedPath = "/" . $pathItems[3] . ($pathItems[4] ? "/" . $pathItems[4] : "");

$router->dispatch($requestedPath);