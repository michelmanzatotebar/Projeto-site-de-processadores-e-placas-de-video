<?php
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Produto.php';

class CarrinhoController {
    private $carrinhoModel;
    private $produtoModel;
    
    public function __construct($db) {
        $this->carrinhoModel = new Carrinho($db);
        $this->produtoModel = new Produto($db);
    }
    
    public function mostrar() {
        try {
            $email_cliente = $_SESSION['cliente_email'] ?? null;
            if (!$email_cliente) {
                header('Location: /cliente/login');
                return;
            }
            
            $itens = $this->carrinhoModel->listByCliente($email_cliente);
            require_once 'Views/carrinho/mostrar.php';
        } catch(PDOException $e) {
            $erro = "Erro ao mostrar carrinho: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function adicionar() {
        try {
            $email_cliente = $_SESSION['cliente_email'] ?? null;
            if (!$email_cliente) {
                header('Location: /cliente/login');
                return;
            }
            
            $produto_id = filter_input(INPUT_POST, 'id_produto', FILTER_SANITIZE_NUMBER_INT);
            $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
            
            $produto = $this->produtoModel->getById($produto_id);
            if (!$produto) {
                header('Location: /produtos');
                return;
            }
            
            if ($this->carrinhoModel->create($email_cliente, $produto_id, $quantidade, $produto['Preco'])) {
                header('Location: /carrinho');
                exit;
            } else {
                $erro = "Erro ao adicionar ao carrinho";
                header('Location: /produto/' . $produto_id);
            }
        } catch(PDOException $e) {
            $erro = "Erro ao adicionar ao carrinho: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function remover() 
    {
        try {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?? 
                  filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            if ($id && $this->carrinhoModel->delete($id)) {
                header('Location: /carrinho');
                exit;
            } else {
                throw new Exception("Não foi possível remover o item do carrinho");
            }
        } catch(PDOException $e) {
            $erro = "Erro ao remover item do carrinho: " . $e->getMessage();
            require_once 'Views/erro.php';
        } catch(Exception $e) {
            $erro = $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
}