<?php
class CarrinhoController {
    private $carrinhoModel;
    private $produtoModel;

    public function __construct() {
        $this->carrinhoModel = new Carrinho();
        $this->produtoModel = new Produto();
    }

    public function mostrar() {
        // Mostra itens do carrinho
        $email_cliente = $_SESSION['cliente_email'] ?? null;
        if(!$email_cliente) {
            header('Location: /cliente/login');
            return;
        }

        $itens = $this->carrinhoModel->buscarItens($email_cliente);
        require_once 'Views/carrinho/mostrar.php';
    }

    public function adicionar() {
        // Adiciona produto ao carrinho
        $email_cliente = $_SESSION['cliente_email'] ?? null;
        if(!$email_cliente) {
            header('Location: /cliente/login');
            return;
        }

        $produto_id = $_POST['id_produto'];
        $quantidade = $_POST['quantidade'];
        $preco = $this->produtoModel->buscarPreco($produto_id);

        $dados = [
            'email_cliente' => $email_cliente,
            'id_produto' => $produto_id,
            'quantidade' => $quantidade,
            'preco_produto' => $preco,
            'preco_total' => $preco * $quantidade
        ];

        if($this->carrinhoModel->adicionar($dados)) {
            header('Location: /carrinho');
        } else {
            $erro = "Erro ao adicionar ao carrinho";
            header('Location: /produto/' . $produto_id);
        }
    }

    public function remover() {
        // Remove item do carrinho
        $email_cliente = $_SESSION['cliente_email'] ?? null;
        $produto_id = $_POST['id_produto'];

        if($this->carrinhoModel->remover($email_cliente, $produto_id)) {
            header('Location: /carrinho');
        }
    }
}