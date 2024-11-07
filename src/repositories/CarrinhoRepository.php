<?php
 class CarrinhoRepository {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function findByClienteEmail($email) {
        $query = "SELECT * FROM Carrinho WHERE email_cliente = ?";
        
    }
    
    public function addItem(Carrinho $item) {
    }
    
    public function updateQuantidade($id, $quantidade) {

    }
    
    public function removeItem($id) {
    }
}