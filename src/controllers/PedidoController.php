<?php
class PedidoController {
    private $pedidoModel;
    private $carrinhoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->carrinhoModel = new Carrinho();
    }

    public function finalizar() {
    
        $email_cliente = $_SESSION['cliente_email'] ?? null;
        if(!$email_cliente) {
            header('Location: /cliente/login');
            return;
        }

        $itens = $this->carrinhoModel->buscarItens($email_cliente);
        require_once 'Views/pedido/finalizar.php';
    }

    public function confirmar() {
        
        $email_cliente = $_SESSION['cliente_email'] ?? null;
        if(!$email_cliente) {
            header('Location: /cliente/login');
            return;
        }

        
        $itens = $this->carrinhoModel->buscarItens($email_cliente);
        

        $total = 0;
        foreach($itens as $item) {
            $total += $item['preco_total'];
        }

        $dados = [
            'email_cliente' => $email_cliente,
            'quantidade' => count($itens),
            'preco_total' => $total
        ];

        if($this->pedidoModel->criar($dados)) {
          $this->carrinhoModel->limpar($email_cliente);
            require_once 'Views/pedido/sucesso.php';
        } else {
            $erro = "Erro ao finalizar pedido";
            require_once 'Views/pedido/finalizar.php';
        }
    }
}