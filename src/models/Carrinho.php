<?php

class Carrinho {
    private $conn;
    
    public function __construct($db) {
        $this->conn = new Carrinho ($db);
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
    
    public function list() {
        $sql = "SELECT * FROM Carrinho";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM Carrinho WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $quantidade) {
        $sql = "UPDATE Carrinho 
                SET Quantidade = :quantidade, 
                    Preco_total = Quantidade * Preco_produto 
                WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Carrinho WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
