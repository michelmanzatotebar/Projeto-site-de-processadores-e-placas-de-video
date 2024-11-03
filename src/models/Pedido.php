<?php
class Pedido {
    private $id;
    private $email_cliente;
    private $quantidade;
    private $preco_total;
    
    public function __construct($id, $email_cliente, $quantidade, $preco_total) {
        $this->id = $id;
        $this->email_cliente = $email_cliente;
        $this->quantidade = $quantidade;
        $this->preco_total = $preco_total;
    }
    
    
    public function getId() { return $this->id; }
    public function getEmailCliente() { return $this->email_cliente; }
    public function getQuantidade() { return $this->quantidade; }
    public function getPrecoTotal() { return $this->preco_total; }
    
    public function finalizarPedido() {
        return true;
    }
}