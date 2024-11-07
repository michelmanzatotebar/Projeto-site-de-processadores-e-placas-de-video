?<?php
class ProdutoController {
    private $produtoModel;
    
    public function __construct($db) {
        $this->produtoModel = new Produto($db);
    }
    
    public function index() {
        try {
            $produtos = $this->produtoModel->list();
            require_once 'Views/produtos/listar.php';
        } catch(PDOException $e) {
            $erro = "Erro ao listar produtos: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function mostrar($id) {
        try {
            $produto = $this->produtoModel->getById($id);
            if (!$produto) {
                header('Location: /produtos');
                return;
            }
            require_once 'Views/produtos/detalhes.php';
        } catch(PDOException $e) {
            $erro = "Erro ao buscar produto: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
    
    public function buscarPorCategoria($categoria) {
        try {
            $sql = "SELECT * FROM Produto WHERE Categoria = :categoria";
            $stmt = $this->produtoModel->getConn()->prepare($sql);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require_once 'Views/produtos/listar.php';
        } catch(PDOException $e) {
            $erro = "Erro ao buscar produtos por categoria: " . $e->getMessage();
            require_once 'Views/erro.php';
        }
    }
}