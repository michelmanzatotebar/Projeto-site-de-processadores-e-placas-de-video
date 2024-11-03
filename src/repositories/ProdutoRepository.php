<?php
class ProdutoRepository {
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM Produto");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $produtos = [];
        foreach ($rows as $row) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['categoria'],
                $row['marca'],
                $row['especificacoes'],
                $row['preco']
            );
        }
        
        return $produtos;
    }
    
    public function findById(int $id): ?Produto {
        $stmt = $this->db->prepare("SELECT * FROM Produto WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Produto(
            $row['id'],
            $row['nome'],
            $row['categoria'],
            $row['marca'],
            $row['especificacoes'],
            $row['preco']
        );
    }
    
    public function save(Produto $produto): bool {
        if ($produto->getId()) {
            return $this->update($produto);
        }
        return $this->insert($produto);
    }
    
    private function insert(Produto $produto): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Produto (nome, categoria, marca, especificacoes, preco) 
             VALUES (?, ?, ?, ?, ?)"
        );
        
        return $stmt->execute([
            $produto->getNome(),
            $produto->getCategoria(),
            $produto->getMarca(),
            $produto->getEspecificacoes(),
            $produto->getPreco()
        ]);
    }
    
    private function update(Produto $produto): bool {
        $stmt = $this->db->prepare(
            "UPDATE Produto 
             SET nome = ?, categoria = ?, marca = ?, especificacoes = ?, preco = ? 
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $produto->getNome(),
            $produto->getCategoria(),
            $produto->getMarca(),
            $produto->getEspecificacoes(),
            $produto->getPreco(),
            $produto->getId()
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM Produto WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
