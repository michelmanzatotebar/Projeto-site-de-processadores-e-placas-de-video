<?php
class Pedido {
    private $conn;
    
    public function __construct($db) {
        $this->conn = New Pedido ($db);
    }
    
    public function create($email_cliente, $quantidade, $preco_total) {
        $sql = "INSERT INTO Pedido (Email_cliente, Quantidade, Preco_total) 
                VALUES (:email_cliente, :quantidade, :preco_total)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email_cliente', $email_cliente);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_total', $preco_total);
        return $stmt->execute();
    }
    
    public function list() {
        $sql = "SELECT * FROM Pedido";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM Pedido WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $quantidade, $preco_total) {
        $sql = "UPDATE Pedido 
                SET Quantidade = :quantidade, Preco_total = :preco_total 
                WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_total', $preco_total);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Pedido WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}