<?php
class Pedido {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($email_cliente, $quantidade, $preco_total) {
        try {
            $stmt = $this->conn->prepare("SELECT Email FROM Cliente WHERE Email = :email");
            $stmt->bindParam(':email', $email_cliente);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                $stmtInsert = $this->conn->prepare("INSERT INTO Cliente (Email, Nome) VALUES (:email, :email)");
                $stmtInsert->bindParam(':email', $email_cliente);
                $stmtInsert->execute();
            }
            
            $sql = "INSERT INTO Pedido (Email_cliente, Quantidade, Preco_total) 
                    VALUES (:email_cliente, :quantidade, :preco_total)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email_cliente', $email_cliente);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':preco_total', $preco_total);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Erro ao criar pedido: " . $e->getMessage());
            throw $e;
        }
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
                SET Quantidade = :quantidade, 
                    Preco_total = :preco_total 
                WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_total', $preco_total);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Pedido WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}