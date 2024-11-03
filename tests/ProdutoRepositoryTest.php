<?php
use PHPUnit\Framework\TestCase;

class ProdutoRepositoryTest extends TestCase {
    private $db;
    private $produtoRepository;
    
    protected function setUp(): void {
        $this->db = $this->createMock(PDO::class);
        $this->produtoRepository = new ProdutoRepository($this->db);
    }
    
    public function testFindAllReturnsProdutos(): void {
      
        $pdoStatement = $this->createMock(PDOStatement::class);
        $expectedData = [
            [
                'id' => 1,
                'nome' => 'Processador Intel i7',
                'categoria' => 'Processador',
                'marca' => 'Intel',
                'especificacoes' => '11th Gen',
                'preco' => 2500.00
            ]
        ];
        
   
        $pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);
            
        $this->db->expects($this->once())
            ->method('query')
            ->willReturn($pdoStatement);
        
        
        $result = $this->produtoRepository->findAll();
        
        
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Produto::class, $result[0]);
        $this->assertEquals(1, $result[0]->getId());
        $this->assertEquals('Processador Intel i7', $result[0]->getNome());
    }
    
    public function testFindByIdReturnsProduto(): void {
       
        $pdoStatement = $this->createMock(PDOStatement::class);
        $expectedData = [
            'id' => 1,
            'nome' => 'Processador Intel i7',
            'categoria' => 'Processador',
            'marca' => 'Intel',
            'especificacoes' => '11th Gen',
            'preco' => 2500.00
        ];
        
        $pdoStatement->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);
            
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);
            
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->with([1]);
        
        
        $result = $this->produtoRepository->findById(1);
        
        
        $this->assertInstanceOf(Produto::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('Processador Intel i7', $result->getNome());
    }
}