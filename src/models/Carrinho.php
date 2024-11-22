<?php

class Carrinho {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($email_cliente, $id_produto, $quantidade, $preco_produto) {
        $preco_total = $quantidade * $preco_produto;
        $sql = "INSERT INTO Carrinho (Email_cliente, Id_produto, Quantidade, Preco_produto, Preco_total) 
                VALUES (:email_cliente, :id_produto, :quantidade, :preco_produto, :preco_total)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email_cliente', $email_cliente);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_produto', $preco_produto);
        $stmt->bindParam(':preco_total', $preco_total);
        return $stmt->execute();
    }
    
    public function listByCliente($email_cliente) {
        $sql = "SELECT c.*, p.Nome as Produto_Nome 
                FROM Carrinho c 
                JOIN Produto p ON c.Id_produto = p.ID 
                WHERE c.Email_cliente = :email_cliente";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email_cliente', $email_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function delete($id) {
        $sql = "DELETE FROM Carrinho WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
