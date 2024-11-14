<?php

use PHPUnit\Framework\TestCase;

class ProdutoControllerTest extends TestCase
{
    private $produtoController;
    private $produtoModel;
    private $db;
    
    protected function setUp(): void
    {
        $this->db = $this->createMock(PDO::class);
        $this->produtoModel = $this->createMock(Produto::class);
        $this->produtoController = new ProdutoController($this->db);
    }
    
    public function testIndexListaTodosProdutos()
    {
        $produtosEsperados = [
            ['ID' => 1, 'Nome' => 'Produto 1', 'Preco' => 100.00],
            ['ID' => 2, 'Nome' => 'Produto 2', 'Preco' => 200.00]
        ];
        
        $this->produtoModel->expects($this->once())
            ->method('list')
            ->willReturn($produtosEsperados);
            
        ob_start();
        $this->produtoController->index();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Views/produtos/listar.php', $output);
    }
    
    public function testMostrarProdutoExistente()
    {
        $produtoEsperado = [
            'ID' => 1,
            'Nome' => 'Produto 1',
            'Preco' => 100.00,
            'Descricao' => 'Descrição do produto'
        ];
        
        $this->produtoModel->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($produtoEsperado);
            
        ob_start();
        $this->produtoController->mostrar(1);
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Views/produtos/detalhes.php', $output);
    }
    
    public function testMostrarRedirecionaQuandoProdutoNaoExiste()
    {
        $this->produtoModel->expects($this->once())
            ->method('getById')
            ->with(999)
            ->willReturn(false);
            
        ob_start();
        $this->produtoController->mostrar(999);
        $headers = xdebug_get_headers();
        ob_end_clean();
        
        $this->assertContains('Location: /produtos', $headers);
    }
    
    public function testBuscarPorCategoriaRetornaProdutos()
    {
        $categoria = 'Eletrônicos';
        $produtosEsperados = [
            ['ID' => 1, 'Nome' => 'Produto 1', 'Categoria' => 'Eletrônicos'],
            ['ID' => 2, 'Nome' => 'Produto 2', 'Categoria' => 'Eletrônicos']
        ];
        
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($produtosEsperados);
            
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);
        
        ob_start();
        $this->produtoController->buscarPorCategoria($categoria);
        $output = ob_get_clean();
       
        $this->assertStringContainsString('Views/produtos/listar.php', $output);
    }
    
    public function testBuscarPorCategoriaLidaComErro()
    {
        $categoria = 'Categoria Inexistente';
        
        $this->db->expects($this->once())
            ->method('prepare')
            ->willThrowException(new PDOException('Erro de banco de dados'));
            
        ob_start();
        $this->produtoController->buscarPorCategoria($categoria);
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Views/erro.php', $output);
        $this->assertStringContainsString('Erro ao buscar produtos por categoria', $output);
    }
}