<?php
class Pedido {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($email_cliente, $quantidade, $preco_total) {
            $sql = "INSERT INTO Pedido (Email_cliente, Quantidade, Preco_total) VALUES (:email_cliente, :quantidade, :preco_total)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email_cliente', $email_cliente);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':preco_total', $preco_total);
            return $stmt->execute();
            
       
    }
    
    public function list() {
        try {
            $sql = "SELECT * FROM Pedido ORDER BY ID DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar pedidos: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT * FROM Pedido WHERE ID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pedido: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM Pedido WHERE ID = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Erro ao deletar pedido: " . $e->getMessage());
            throw $e;
        }
    }
}