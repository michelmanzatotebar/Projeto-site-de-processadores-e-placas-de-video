<?php
class Carrinho {
    private $id;
    private $email_cliente;
    private $id_produto;
    private $quantidade;
    private $preco_produto;
    private $preco_total;
    
    public function __construct($id, $email_cliente, $id_produto, $quantidade, $preco_produto) {
        $this->id = $id;
        $this->email_cliente = $email_cliente;
        $this->id_produto = $id_produto;
        $this->quantidade = $quantidade;
        $this->preco_produto = $preco_produto;
        $this->calcularPrecoTotal();
    }
    
    private function calcularPrecoTotal() {
        $this->preco_total = $this->quantidade * $this->preco_produto;
    }
    
 
    public function getId() { return $this->id; }
    public function getEmailCliente() { return $this->email_cliente; }
    public function getIdProduto() { return $this->id_produto; }
    public function getQuantidade() { return $this->quantidade; }
    public function getPrecoProtudo() { return $this->preco_produto; }
    public function getPrecoTotal() { return $this->preco_total; }
    
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
        $this->calcularPrecoTotal();
    }
}
