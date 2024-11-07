<?php
class PedidoController {
    private $pedidoModel;
    private $carrinhoModel;
    
    public function __construct($db) {
        $this->pedidoModel = new Pedido($db);
        $this->carrinhoModel = new Carrinho($db);
    }
    
    public function finalizar() {
        try {
            $email_cliente = $_SESSION['cliente_email'] ?? null;
            if (!$email_cliente) {
                header('Location: /cliente/login');
                return;
            }
            
            $itens = $this->carrinhoModel->listByCliente($email_cliente);
            require_once 'Views/pedido/finalizar.php';
        } catch(PDOException $e) {
            $erro = "Erro ao finalizar pedido: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function confirmar() {
        try {
            $email_cliente = $_SESSION['cliente_email'] ?? null;
            if (!$email_cliente) {
                header('Location: /cliente/login');
                return;
            }
            
            $itens = $this->carrinhoModel->listByCliente($email_cliente);
            if (empty($itens)) {
                header('Location: /carrinho');
                return;
            }
            
            $total = array_reduce($itens, function($acc, $item) {
                return $acc + $item['Preco_total'];
            }, 0);
            
            if ($this->pedidoModel->create($email_cliente, count($itens), $total)) {
                foreach ($itens as $item) {
                    $this->carrinhoModel->delete($item['ID']);
                }
                require_once 'Views/pedido/sucesso.php';
            } else {
                $erro = "Erro ao finalizar pedido";
                require_once 'Views/pedido/finalizar.php';
            }
        } catch(PDOException $e) {
            $erro = "Erro ao confirmar pedido: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
}