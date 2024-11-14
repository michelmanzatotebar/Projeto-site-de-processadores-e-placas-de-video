<?php
class Cliente {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($nome, $endereco, $numero, $email, $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Cliente (Nome, Endereco, Numero, Email, Senha) 
                VALUES (:nome, :endereco, :numero, :email, :senha)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);
        return $stmt->execute();
    }
    
    public function getByEmail($email) {
        $sql = "SELECT * FROM Cliente WHERE Email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $nome, $endereco, $numero, $email) {
        $sql = "UPDATE Cliente 
                SET Nome = :nome, 
                    Endereco = :endereco, 
                    Numero = :numero, 
                    Email = :email 
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
