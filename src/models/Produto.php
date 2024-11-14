<?php
class Produto {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create($nome, $categoria, $marca, $especificacoes, $preco) {
        $sql = "INSERT INTO Produto (Nome, Categoria, Marca, Especificacoes, Preco) 
                VALUES (:nome, :categoria, :marca, :especificacoes, :preco)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':especificacoes', $especificacoes);
        $stmt->bindParam(':preco', $preco);
        return $stmt->execute();
    }
    
    public function list() {
        $sql = "SELECT * FROM Produto";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM Produto WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $nome, $categoria, $marca, $especificacoes, $preco) {
        $sql = "UPDATE Produto 
                SET Nome = :nome, Categoria = :categoria, Marca = :marca, 
                    Especificacoes = :especificacoes, Preco = :preco 
                WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':especificacoes', $especificacoes);
        $stmt->bindParam(':preco', $preco);
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM Produto WHERE ID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
