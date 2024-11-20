<?php
require_once __DIR__  . '/../controllers/CarrinhoController.php';

$CarrinhoController = new CarrinhoController($pdo);

$router->add('GET','/Carrinho', [$CarrinhoController, 'list']);
$router->add('GET','/Carrinho/{id}', [$CarrinhoController, 'getById']);
$router->add('DELETE','/Carrinho/{id}', [$CarrinhoController, 'delete']);
$router->add('POST','/Carrinho', [$CarrinhoController, 'getById']);
$router->add('PUT','/Carrinho', [$CarrinhoController, 'getById']);