<?php
class Cliente {
    private $conn;
    
    public function __construct($db) {
        $this->conn = new Cliente($db);
    }
    
    public function create($nome, $endereco, $numero, $email) {
        $sql = "INSERT INTO Cliente (Nome, Endereco, Numero, Email) 
                VALUES (:nome, :endereco, :numero, :email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
    
    public function list() {
        $sql = "SELECT * FROM Cliente";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM Cliente WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $nome, $endereco, $numero, $email) {
        $sql = "UPDATE Cliente 
                SET Nome = :nome, Endereco = :endereco, 
                    Numero = :numero, Email = :email 
                WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Cliente WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
