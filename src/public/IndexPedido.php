<?php
require_once __DIR__  . '/../controllers/PedidoController.php';

$PedidoController = new PedidoController($pdo);

$router->add('GET','/Pedido', [$PedidoController, 'list']);
$router->add('GET','/Pedido/{id}', [$PedidoController, 'getById']);
$router->add('DELETE','/Pedido/{id}', [$PedidoController, 'delete']);
$router->add('POST','/Pedido', [$PedidoController, 'getById']);
$router->add('PUT','/Pedido', [$PedidoController, 'getById']);