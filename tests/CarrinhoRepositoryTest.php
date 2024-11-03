<?php
class CarrinhoRepositoryTest extends TestCase {
    private $db;
    private $carrinhoRepository;
    
    protected function setUp(): void {
        $this->db = $this->createMock(PDO::class);
        $this->carrinhoRepository = new CarrinhoRepository($this->db);
    }
    
    public function testFindByClienteEmailReturnsCarrinhoItems(): void {
        
        $email = 'test@example.com';
        $pdoStatement = $this->createMock(PDOStatement::class);
        $expectedData = [
            [
                'id' => 1,
                'email_cliente' => 'test@example.com',
                'id_produto' => 1,
                'quantidade' => 2,
                'preco_produto' => 2500.00,
                'preco_total' => 5000.00,
                'produto_nome' => 'Processador Intel i7'
            ]
        ];
        
        $pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($expectedData);
            
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);
            
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->with([$email]);
        
        
        $result = $this->carrinhoRepository->findByClienteEmail($email);
        
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Carrinho::class, $result[0]);
        $this->assertEquals('test@example.com', $result[0]->getEmailCliente());
        $this->assertEquals(2, $result[0]->getQuantidade());
    }
    
    public function testAddItemInsertsCarrinhoItem(): void {
        
        $pdoStatement = $this->createMock(PDOStatement::class);
        $item = new Carrinho(
            null,
            'test@example.com',
            1,
            2,
            2500.00
        );
        
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);
            
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        
        $result = $this->carrinhoRepository->addItem($item);
        
        
        $this->assertTrue($result);
    }
    
    public function testUpdateQuantidadeUpdatesCarrinhoItem(): void {
        
        $pdoStatement = $this->createMock(PDOStatement::class);
        
        $this->db->expects($this->once())
            ->method('prepare')
            ->willReturn($pdoStatement);
            
        $pdoStatement->expects($this->once())
            ->method('execute')
            ->with([3, 1])
            ->willReturn(true);
        
        
        $result = $this->carrinhoRepository->updateQuantidade(1, 3);
        
        
        $this->assertTrue($result);
    }
}